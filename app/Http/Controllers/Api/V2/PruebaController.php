<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PruebaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v2/prueba",
     *     summary="Prueba",
     *     description="Prueba",
     *     tags={"Prueba"},
     *     @OA\Response(
     *         response=200,
     *         description="Prueba",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Prueba")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(['message' => 'Prueba']);
    }
}
