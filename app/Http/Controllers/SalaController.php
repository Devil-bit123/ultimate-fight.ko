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
     * @OA\Get(
     *     path="/api/salas",
     *     summary="Obtener salas no bloqueadas",
     *     description="Recupera todas las salas cuyo estado no es 'bloqueada'.",
     *     tags={"Salas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de salas obtenida con éxito.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="salas",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Sala A"),
     *                     @OA\Property(property="estado", type="string", example="disponible")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron salas no bloqueadas."
     *     )
     * )
     */
    public function index()
    {
        $salas = Sala::where('estado', '!=', 'bloqueada')->get();
        return response()->json(['salas' => $salas], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/salas/encurso",
     *     summary="Obtener salas bloqueadas",
     *     description="Recupera todas las salas cuyo estado es 'bloqueada'.",
     *     tags={"Salas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de salas bloqueadas obtenida con éxito.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="salas",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Sala B"),
     *                     @OA\Property(property="estado", type="string", example="bloqueada")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron salas bloqueadas."
     *     )
     * )
     */
    public function en_curso()
    {
        $salas = Sala::where('estado', '=', 'bloqueada')->get();
        return response()->json(['salas' => $salas], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/salas",
     *     summary="Crear una nueva sala",
     *     description="Crea una nueva sala con un jugador inicial y establece el estado en 'abierta'.",
     *     tags={"Salas"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para crear una sala",
     *         @OA\JsonContent(
     *             required={"jugador1_id"},
     *             @OA\Property(property="jugador1_id", type="integer", example=123, description="ID del jugador que crea la sala")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sala creada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="sala",
     *                 type="object",
     *                 @OA\Property(property="uuid", type="string", example="a9b8c7d6-e5f4-3210-b9a8-c7d6e5f43210"),
     *                 @OA\Property(property="jugador1_id", type="integer", example=123),
     *                 @OA\Property(property="estado", type="string", example="['abierta', 'bloqueada', 'en_uso', 'terminada']")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos de entrada no válidos"
     *     )
     * )
     */
    public function crearSala(Request $request)
    {
        $sala = Sala::create([
            'uuid' => Str::uuid(),
            'jugador1_id' => $request->jugador1_id,
            'estado' => 'abierta',
        ]);

        return response()->json(['sala' => $sala]);
    }


    /**
     * @OA\Post(
     *     path="/api/salas/{uuid}/unirse",
     *     summary="Unirse a una sala",
     *     description="Permite a un jugador unirse a una sala abierta utilizando el UUID de la sala.",
     *     tags={"Salas"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID de la sala a la que se desea unir",
     *         @OA\Schema(type="string", example="a9b8c7d6-e5f4-3210-b9a8-c7d6e5f43210")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para unirse a la sala",
     *         @OA\JsonContent(
     *             required={"jugador2_id"},
     *             @OA\Property(property="jugador2_id", type="integer", example=456, description="ID del jugador que se une a la sala")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jugador unido a la sala con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Jugador unido a la sala.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Sala no válida o error en la solicitud",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="El jugador no puede unirse a la sala.")
     *         )
     *     )
     * )
     */
    public function unirseASala(Request $request, $uuid)
    {
        $sala = Sala::where('uuid', $uuid)->first();
        //dd($request->jugador1_id);
        if ($sala->jugador1_id == $request->jugador2_id) {
            return response()->json(['message' => 'El jugador no puede unirse a la sala.'], 400);
        }


        if (!$sala || $sala->estado !== 'abierta') {
            return response()->json(['message' => 'Sala no válida o ya bloqueada.'], 400);
        }

        // Unir jugador a la sala
        $sala->jugador2_id = $request->jugador2_id;
        $sala->estado = 'bloqueada';
        $sala->save();

        return response()->json(['message' => 'Jugador unido a la sala.']);
    }

}
