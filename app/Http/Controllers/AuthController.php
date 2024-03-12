<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication",
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user and returns a token along with user details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abcdefghijk...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email has already been taken."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request) {
        $field = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $field['name'],
            'email' => $field['email'],
            'password' => bcrypt($field['password'])            
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Auth"},
     *     summary="Logs in a user",
     *     description="Authenticates user and returns a token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcdefghijk...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid email or wrong password")
     *         )
     *     )
     * )
     */
    public function login(Request $request) {
        $field = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $field['email'])->first();

        if (!$user) {
            return response(['message'=> 'Invalid email'], 401);
        }

        if (!Hash::check($field['password'], $user->password)) {
            return response(['message' =>'Wrong password'], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response(['token' => $token], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logoutUser",
     *     tags={"Auth"},
     *     summary="Logs out a user",
     *     description="Invalidates the user's authentication token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function logout(){
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
