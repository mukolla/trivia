<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Player",
 *     title="Player",
 *     description="A player object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The user's ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The user's name",
 *         example="John",
 *     ),
 *     @OA\Property(
 *         property="answers",
 *         type="integer",
 *         description="Desc. answers",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="points",
 *         type="integer",
 *         description="Desc. points",
 *         example=1,
 *     ),
 *     required={"id", "name", "answers", "points"}
 * )
 */
class Player extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'answers', 'points'];
}
