<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\LoginFormRequest;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="APIs for user authentication"
 * )
 */

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @OA\Post(
     *      path="/api/register",
     *      summary="Register a new user",
     *      description="Register a new user with provided details.",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RegisterFormRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User is created successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="User is created successfully."),
     *                  @OA\Property(property="data", type="object",
     *                      @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi...")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid.")
     *          )
     *      )
     * )
     *
     * @param RegisterFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterFormRequest $request)
    {
        $user = $this->createUser($request);

        $token = $user->createToken('token')->accessToken;

        $data['user'] = $user;

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
            'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Log in a user with email and password.
     *
     * @OA\Post(
     *      path="/api/login",
     *      summary="Log in a user",
     *      description="Log in a user with email and password.",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginFormRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User is logged in successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="User is logged in successfully."),
     *                  @OA\Property(property="data", type="object",
     *                      @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi...")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="failed"),
     *              @OA\Property(property="message", type="string", example="Invalid credentials")
     *          )
     *      )
     * )
     *
     * @param LoginFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginFormRequest $request)
    {
        $user = $this->attemptLogin($request);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken($request->email)->accessToken;
        $data['token'] = $token;
        $data['user'] = $user;

        $response = [
            'status' => 'success',
            'message' => 'User is logged in successfully.',
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    /**
     * Log out the authenticated user.
     *
     * @OA\Post(
     *      path="/api/logout",
     *      summary="Log out the authenticated user",
     *      description="Log out the authenticated user.",
     *      tags={"Authentication"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="User is logged out successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="User is logged out successfully")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="message", type="string", example="Unauthenticated.")
     *              )
     *          )
     *      )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
        ], 200);
    }

    /**
     * Create a new user instance.
     *
     * @param RegisterFormRequest $request
     * @return User
     */
    private function createUser(RegisterFormRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    /**
     * Attempt to log in a user.
     *
     * @param LoginFormRequest $request
     * @return User|null
     */
    private function attemptLogin(LoginFormRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }
}
