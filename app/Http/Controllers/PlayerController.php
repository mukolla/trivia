<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlayerRequest;
use App\Http\Resources\Player as PlayerResource;
use App\Http\Resources\PlayerCollection;
use App\Models\Player;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="My First API",
 *     version="0.1"
 * )
 */
class PlayerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/players",
     *     summary="Get all players",
     *     description="Retrieve a list of all players",
     *     tags={"Players"},
     *
     *     @OA\Response(
     *          response=200,
     *          description="Players list",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Player")),
     *         ),
     *      ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        return new PlayerCollection(Player::all());
    }

    /**
     * @OA\Get(
     *     path="/api/players/{id}",
     *     summary="Get a single player",
     *     description="Returns a single player by ID",
     *     operationId="getPlayerById",
     *     tags={"Players"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         description="ID of the player to return",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Player"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Player not found"
     *     )
     * )
     */
    public function show($id)
    {
        return new PlayerResource(Player::findOrFail($id));
    }

    /**
     * @OA\Post(
     *     path="/api/players",
     *     summary="Create a new player",
     *     description="Create a new player with name, answers, and points",
     *     tags={"Players"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data of a new player",
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Player name",
     *                 example="John"
     *             ),
     *             @OA\Property(
     *                 property="answers",
     *                 type="integer",
     *                 description="Number of correct answers",
     *                 example="10"
     *             ),
     *             @OA\Property(
     *                 property="points",
     *                 type="integer",
     *                 description="Number of points earned",
     *                 example="100"
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful creation of a new player",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object", properties={
     *             @OA\Property(property="player", ref="#/components/schemas/Player")
     *         })
     *        ),
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Invalid request data",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Error message",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Object with errors for each invalid field",
     *                 example={
     *                     "name": {"The name field is required."},
     *                     "points": {"The points must be an integer."}
     *                 }
     *             ),
     *         ),
     *     ),
     * )
     */
    public function store(CreatePlayerRequest $request)
    {
        $player = Player::create($request->all());

        return (new PlayerResource($player))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Post(
     *     path="/api/players/{id}/answer",
     *     summary="Submit an answer for a player",
     *     description="Submit a player's answer and calculate the points earned based on whether it was correct or not.",
     *     operationId="answer",
     *     tags={"Players"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the player",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="The request body contains the player's answer.",
     *
     *         @OA\JsonContent(
     *             required={"correct"},
     *
     *             @OA\Property(
     *                 property="correct",
     *                 type="boolean",
     *                 description="Whether the player's answer was correct or not.",
     *                 example=true
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Player")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Player not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Player not found."
     *             )
     *         )
     *     )
     * )
     */
    public function answer($id, Request $request)
    {
        $request->merge(['correct' => (bool) json_decode($request->get('correct'))]);
        $request->validate([
            'correct' => 'required|boolean',
        ]);

        $player = Player::findOrFail($id);
        $player->answers++;
        $player->points = ($request->get('correct')
            ? $player->points + 1
            : $player->points - 1);
        $player->save();

        return new PlayerResource($player);
    }

    /**
     * @OA\Delete(
     *     path="/players/{id}",
     *     summary="Delete player by ID",
     *     description="Delete a player record by its ID",
     *     operationId="deletePlayerById",
     *     tags={"Players"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the player",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Player record deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Player not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Player not found"
     *             )
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Delete(
     *     path="/api/players/{id}/answers",
     *     summary="Reset a player's answers",
     *     description="Reset the number of answers of a player to 0",
     *     operationId="resetAnswers",
     *     tags={"Players"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the player",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful reset of player's answers",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Player")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Player not found"
     *     )
     * )
     */
    public function resetAnswers($id)
    {
        $player = Player::findOrFail($id);
        $player->answers = 0;
        $player->points = 0;

        return new PlayerResource($player);
    }
}
