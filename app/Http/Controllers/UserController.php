<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SignInUserRequest;
use App\Http\Requests\User\SignUpUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    public function signUp(SignUpUserRequest $request)
    {

        try {
            $user = User::create([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "address" => $request->address,
                "phone_number" => $request->phone_number,
                "password" => $request->password,
            ]);


            return response()->json(["user" => $user] , 201);

        } catch (\Throwable $th) {
            return response()->json($th);

        }



    }
    public function signIn(SignInUserRequest $request)
    {
        try {
            $user = User::where("email", $request->email)->first();

            if (is_null($user) || !Hash::check($request->password, $user->password)) {
                return response()->json(["message" => "Credantials are wrong"], 401);
            }

            $token = $user->createToken("name")->plainTextToken;
            return response()->json([
                "message" => "logged in successfully",
                "data" => [
                    "id" => $user->id,
                    "email" => $user->email,
                    "role" => $user->role
                ]
                ,
                "token" => $token
            ], 200);


        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
