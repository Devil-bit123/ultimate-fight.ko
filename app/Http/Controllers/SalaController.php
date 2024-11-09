<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="Sala",
 *     type="object",
 *     required={"uuid", "jugador1_id", "estado"},
 *     @OA\Property(property="uuid", type="string", example="a1b2c3d4-e5f6-7g8h-9i0j-k1l2m3n4o5p6"),
 *     @OA\Property(property="jugador1_id", type="integer", example=1),
 *     @OA\Property(property="jugador2_id", type="integer", example=2, nullable=true),
 *     @OA\Property(property="jugador1_personaje_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="jugador2_personaje_id", type="integer", example=2, nullable=true),
 *     @OA\Property(property="estado", type="string", example="abierta"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-30T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-30T12:00:00Z")
 * )
 */

class SalaController extends Controller
{

    /**
     * @OA\Post(
     *     path="/salas",
     *     tags={"Salas"},
     *     summary="Crear una nueva sala",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="jugador1_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sala creada con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="uuid", type="string", example="a1b2c3d4-e5f6-7g8h-9i0j-k1l2m3n4o5p6")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object")
     *         )
     *     )
     * )
     */
    public function crearSala(Request $request)
    {
        $request->validate([
            'jugador1_id' => 'required|exists:users,id',
        ]);

        $sala = Sala::create([
            'uuid' => (string) Str::uuid(),
            'jugador1_id' => $request->jugador1_id,
            'estado' => 'abierta',
        ]);

        return response()->json(['uuid' => $sala->uuid], 201);
    }

    /**
     * @OA\Post(
     *     path="/salas/{uuid}/unirse",
     *     tags={"Salas"},
     *     summary="Unirse a una sala existente",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID de la sala a la que unirse",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="jugador2_id", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unido a la sala correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unido a la sala correctamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sala no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sala no encontrada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La sala está llena",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La sala está llena.")
     *         )
     *     )
     * )
     */
    public function unirseSala(Request $request, $uuid)
    {
        $sala = Sala::where('uuid', $uuid)->first();

        if (!$sala) {
            return response()->json(['message' => 'Sala no encontrada.'], 404);
        }

        if ($sala->estado === 'cerrada') {
            return response()->json(['message' => 'La sala está llena.'], 400);
        }

        $sala->jugador2_id = $request->jugador2_id;
        $sala->estado = 'cerrada'; // Marca la sala como llena
        $sala->save();

        return response()->json(['message' => 'Unido a la sala correctamente.'], 200);
    }

    /**
     * @OA\Post(
     *     path="/salas/{uuid}/seleccionar-personaje",
     *     tags={"Salas"},
     *     summary="Seleccionar un personaje en la sala",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID de la sala",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="jugador_id", type="integer", example=1),
     *             @OA\Property(property="personaje_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Personaje seleccionado correctamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Personaje seleccionado correctamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="El jugador no está en esta sala",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No estás en esta sala.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object")
     *         )
     *     )
     * )
     */
    public function seleccionarPersonaje(Request $request, $uuid)
    {
        $request->validate([
            'jugador_id' => 'required|exists:users,id',
            'personaje_id' => 'required|exists:personajes,id',
        ]);

        $sala = Sala::where('uuid', $uuid)->first();

        // Lógica para asegurar que el jugador pertenece a la sala
        if ($sala->jugador1_id === $request->jugador_id) {
            $sala->jugador1_personaje_id = $request->personaje_id; // Guarda el personaje del jugador 1
        } elseif ($sala->jugador2_id === $request->jugador_id) {
            $sala->jugador2_personaje_id = $request->personaje_id; // Guarda el personaje del jugador 2
        } else {
            return response()->json(['message' => 'No estás en esta sala.'], 403);
        }

        $sala->save();

        return response()->json(['message' => 'Personaje seleccionado correctamente.'], 200);
    }
}
