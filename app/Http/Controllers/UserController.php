<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/register",
     *     summary="Register a new user",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", maxLength=255, description="Name of the user"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, description="Email of the user"),
     *             @OA\Property(property="password", type="string", minLength=8, description="Password of the user"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successfully registered user",
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
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *
     *                     @OA\Items(type="string")
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function register(CreateUserRequest $request)
    {
        $user = User::addNewUser(
            $request->email,
            Hash::make($request->password),
            $request->name,
        );

        return (new UserResource($user))
            ->additional(['message' => 'Successfully registered user!'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
