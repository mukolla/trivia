<?php

namespace App\Http\Controllers;

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
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Player")
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
     *         @OA\JsonContent(ref="#/components/schemas/Player")
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $player = Player::create($request->all());

        return (new PlayerResource($player))
            ->response()
            ->setStatusCode(201);
    }

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

    public function delete($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json(null, 204);
    }

    public function resetAnswers($id)
    {
        $player = Player::findOrFail($id);
        $player->answers = 0;
        $player->points = 0;

        return new PlayerResource($player);
    }
}
