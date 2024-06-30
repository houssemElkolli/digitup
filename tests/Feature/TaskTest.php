<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;


class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_tasks_for_users_with_role_admin(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Task::factory()->count(3)->for($user)->create();


        $response = $this->getJson('/api/tasks?includeDeletedTasks=true', ["Accept" => "application/json"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [

                        'id',
                        'title',
                        'description',
                        'status',
                        'expiry_date',
                        'user_id',
                    ]
                ]
            ]);
    }
    public function test_get_all_tasks_for_users_with_role_user(): void
    {
        $user = User::factory()->create(["role" => "user"]);
        Sanctum::actingAs($user);

        Task::factory()->count(3)->for($user)->create();


        $response = $this->getJson('/api/tasks', ["Accept" => "application/json"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [

                        'id',
                        'title',
                        'description',
                        'status',
                        'expiry_date',
                        'user_id',
                    ]
                ]
            ]);
    }
    public function test_store_task(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->for($user)->create();


        $response = $this->post('/api/tasks', $task->toArray(), ["Accept" => "application/json"]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'expiry_date',
                    'user_id',

                ]
            ]);
        $this->assertDatabaseHas('tasks', $task->toArray());

    }
    public function test_show_task(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->for($user)->create();


        $response = $this->get('/api/tasks/' . $task->id, ["Accept" => "application/json"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'expiry_date',
                    'user_id',
                ]
            ]);
    }
    public function test_update_task_for_the_owner(): void
    {
        $user = User::factory()->create(["role" => "user"]);
        Sanctum::actingAs($user);

        $task = Task::factory()->for($user)->create();

        $response = $this->put('/api/tasks/' . $task->id, $task->toArray(), ["Accept" => "application/json"]);

        $response->assertStatus(201);

    }
    public function test_update_task_for__the_admin_but_he_is_not_the_owner(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(["role" => "user"]);
        Sanctum::actingAs($admin);

        $task = Task::factory()->for($user)->create();

        $response = $this->put('/api/tasks/' . $task->id, $task->toArray(), ["Accept" => "application/json"]);

        $response->assertStatus(201);

    }
    public function test_delete_task(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->for($user)->create();

        $response = $this->delete('/api/tasks/' . $task->id, $task->toArray(), ["Accept" => "application/json"]);

        $response->assertStatus(204);

    }
    public function test_view_trashed_tasks(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Task::factory()->for($user)->count(10)->create(["deleted_at" => now()]);

        $response = $this->get('/api/tasks/deleted', ["Accept" => "application/json"]);
        $response->assertStatus(200);

    }
}
