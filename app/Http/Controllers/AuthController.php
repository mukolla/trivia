<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JwtAuthMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'webLogin']]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getAuthToken()
    {
        $credentials = request(['email', 'password']);

        if (! Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('api')->user();

        return JWTAuth::fromUser($user);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     operationId="userLogin",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User email",
     *                     example="mukolla5@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="User password",
     *                     example="123456dummy"
     *                 ),
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="access_token",
     *                 type="string",
     *                 description="JWT access token",
     *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6L2xvY2FsaG9zdDo4MDAwL2FwaS9sb2dpbiIsImlhdCI6MTYzMjI0MTU5NCwiZXhwIjoxNjMyMjQ1MTk0LCJuYmYiOjE2MzIyNDE1OTQsImp0aSI6ImhTR1kwZk1KNUVyTGFyX2sifQ.uUEy3Sbp8SvJU6yC8zGckUOvbeVOG0IUPxHp3eW3f0g"
     *             ),
     *             @OA\Property(
     *                 property="token_type",
     *                 type="string",
     *                 description="Type of the token",
     *                 example="bearer"
     *             ),
     *             @OA\Property(
     *                 property="expires_in",
     *                 type="integer",
     *                 description="Token expiration time in seconds",
     *                 example="3600"
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Error message",
     *                 example="Unauthorized"
     *             ),
     *         ),
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        return response()->json([
            'access_token' => $this->getAuthToken(),
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/web/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     operationId="userWeblogin",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User email",
     *                     example="user@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="User password",
     *                     example="password"
     *                 ),
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="token_type",
     *                 type="string",
     *                 description="Type of the token",
     *                 example="bearer"
     *             ),
     *             @OA\Property(
     *                 property="expires_in",
     *                 type="integer",
     *                 description="Token expiration time in seconds",
     *                 example="3600"
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Error message",
     *                 example="Unauthorized"
     *             ),
     *         ),
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function webLogin()
    {
        return response()->json([
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ])->cookie(JwtAuthMiddleware::COOKIE_JWT_TOKEN_NAME, $this->getAuthToken(), JWTAuth::factory()->getTTL());
    }

    /**
     * Get the authenticated user's details.
     *
     * @OA\Post(
     *     path="/api/auth/me",
     *     summary="Get authenticated user details",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     operationId="authMe",
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", description="ID of the registered user"),
     *             @OA\Property(property="name", type="string", description="Name of the registered user"),
     *             @OA\Property(property="email", type="string", format="email", description="Email of the registered user"),
     *             @OA\Property(property="message", type="string", description="Success message"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Internal Server Error"
     *             )
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"BearerAuth": {}}},
     *     operationId="userLogout",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Logout success message",
     *                 example="Successfully logged out"
     *             ),
     *         ),
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $jwtToken = request()->cookie(JwtAuthMiddleware::COOKIE_JWT_TOKEN_NAME);

        if (! empty($jwtToken)) {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Logged out successfully'])
                ->withCookie(cookie()->forget(JwtAuthMiddleware::COOKIE_JWT_TOKEN_NAME));
        } else {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }
    }

    /**
     * Refresh a token.
     *
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Refresh JWT token",
     *     tags={"Authentication"},
     *     security={{"BearerAuth": {}}},
     *     operationId="userRefresh",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful token refresh",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="access_token",
     *                 type="string",
     *                 description="New JWT access token",
     *                 example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
     *             ),
     *             @OA\Property(
     *                 property="token_type",
     *                 type="string",
     *                 description="Token type",
     *                 example="bearer"
     *             ),
     *             @OA\Property(
     *                 property="expires_in",
     *                 type="integer",
     *                 description="Token expiration time in seconds",
     *                 example=3600
     *             ),
     *         ),
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $jwtToken = request()->cookie(JwtAuthMiddleware::COOKIE_JWT_TOKEN_NAME);
        if (! empty($jwtToken)) {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            Cookie::queue('jwt_token', $newToken, JWTAuth::factory()->getTTL());

            return response()->json([
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ])->cookie(JwtAuthMiddleware::COOKIE_JWT_TOKEN_NAME, $newToken, JWTAuth::factory()->getTTL());
        } else {
            return $this->respondWithToken(auth()->refresh());
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
