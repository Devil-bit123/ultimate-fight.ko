<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Personaje;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Personaje",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Guerrero"),
 *     @OA\Property(property="vida", type="integer", example=140, minimum=130, maximum=150),
 *     @OA\Property(property="ataque", type="integer", example=35, minimum=25, maximum=45),
 *     @OA\Property(property="defensa", type="integer", example=30, minimum=20, maximum=50),
 *     @OA\Property(property="velocidad", type="integer", example=85, minimum=70, maximum=100),
 *     @OA\Property(
 *         property="habilidades",
 *         type="object",
 *         @OA\Property(property="puño_derecho", type="integer", example=40, minimum=20, maximum=50),
 *         @OA\Property(property="puño_izquierdo", type="integer", example=35, minimum=20, maximum=50),
 *         @OA\Property(property="patada_derecha", type="integer", example=45, minimum=20, maximum=50),
 *         @OA\Property(property="patada_izquierda", type="integer", example=30, minimum=20, maximum=50),
 *         @OA\Property(property="ataque_especial", type="integer", example=60, minimum=30, maximum=80)
 *     ),
 *     @OA\Property(property="creado_at", type="string", format="date-time", example="2024-10-30T12:00:00Z"),
 *     @OA\Property(property="actualizado_at", type="string", format="date-time", example="2024-10-30T12:00:00Z")
 * )
 */
class PersonajesController extends Controller
{
    /**
     * @OA\Get(
     *     path="api/personajes",
     *     tags={"Personajes"},
     *     summary="Obtener lista de personajes",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personajes devuelta con éxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Personaje"))
     *     )
     * )
     */
    public function index()
    {
        $personajes = Personaje::all();
        return response()->json(['personajes' => $personajes], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/personajes",
     *     summary="Crear un nuevo personaje",
     *     description="Crea un personaje con un nombre, vida, porcentaje de fallos y habilidades.",
     *     tags={"Personajes"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para crear un personaje",
     *         @OA\JsonContent(
     *             required={"nombre", "vida", "miss_percent", "habilidades"},
     *             @OA\Property(property="nombre", type="string", example="Ezra Thompson", description="Nombre del personaje"),
     *             @OA\Property(property="vida", type="integer", example=150, description="Cantidad de vida del personaje"),
     *             @OA\Property(property="miss_percent", type="integer", example=28, description="Porcentaje de fallo en ataques del personaje"),
     *             @OA\Property(property="habilidades", type="object", example={"punio_derecho": 45, "patada_derecha": 60, "ataque_especial": 55, "punio_izquierdo": 39, "patada_izquierda": 34}, description="Objeto con las habilidades del personaje")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Personaje creado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="personaje",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="nombre", type="string", example="Ezra Thompson"),
     *                 @OA\Property(property="vida", type="integer", example=150),
     *                 @OA\Property(property="miss_percent", type="integer", example=28),
     *                 @OA\Property(property="habilidades", type="object", example={"punio_derecho": 45, "patada_derecha": 60, "ataque_especial": 55, "punio_izquierdo": 39, "patada_izquierda": 34}),
     *                 @OA\Property(property="created_at", type="string", example="2024-11-09 23:53:57"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-11-10 00:48:33")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos de entrada no válidos"
     *     )
     * )
     */
    public function crearPersonaje(Request $request)
    {
        $personaje = Personaje::create([
            'nombre' => $request->nombre,
            'vida' => $request->vida,
            'miss_percent' => $request->miss_percent,
            'habilidades' => $request->habilidades,
        ]);

        return response()->json(['personaje' => $personaje]);
    }


    /**
     * @OA\Post(
     *     path="/api/salas/{uuid}/personajes",
     *     summary="Asignar un personaje a una sala",
     *     description="Este endpoint asigna un personaje y un jugador a una sala, y guarda los valores de vida y porcentaje de fallo del personaje.",
     *     tags={"Salas", "Personajes"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID de la sala",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para asignar un personaje a una sala",
     *         @OA\JsonContent(
     *             required={"sala_id", "personaje_id", "jugador_id"},
     *             @OA\Property(property="sala_id", type="integer", example=1, description="ID de la sala"),
     *             @OA\Property(property="personaje_id", type="integer", example=4, description="ID del personaje a asignar"),
     *             @OA\Property(property="jugador_id", type="integer", example=7, description="ID del jugador que será asignado al personaje")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Personaje y jugador asignados correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Personaje y jugador asociados correctamente a la sala."),
     *             @OA\Property(property="sala", type="object", ref="#/components/schemas/Sala"),
     *             @OA\Property(property="personaje", type="object", ref="#/components/schemas/Personaje"),
     *             @OA\Property(property="jugador", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos de entrada no válidos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error en los datos de entrada.")
     *         )
     *     )
     * )
     */
    public function asignarPersonajeASala(Request $request, $uuid)
    {
        // Validación de los datos de entrada
        $request->validate([
            'sala_id' => 'required|exists:salas,id',
            'personaje_id' => 'required|exists:personajes,id',
            'jugador_id' => 'required|exists:users,id',
        ]);

        // Obtener los datos de la sala, personaje y jugador
        $sala = Sala::find($request->sala_id);
        $personaje = Personaje::find($request->personaje_id);
        $jugador = User::find($request->jugador_id);
        //dd($personaje);
        // Asociar el personaje y el jugador a la sala, y agregar los valores de vida y daño
        $sala->personajes()->attach($request->personaje_id, [
            'jugador_id' => $request->jugador_id,
            'vida_personaje' => $personaje->vida, // Usamos el valor por defecto de 100 si no existe
            'miss_percent' => $personaje->miss_percent, // Usamos el valor por defecto de 10 si no existe
        ]);

        return response()->json([
            'message' => 'Personaje y jugador asociados correctamente a la sala.',
            'sala' => $sala,
            'personaje' => $personaje,
            'jugador' => $jugador,
        ], 200);
    }
}
