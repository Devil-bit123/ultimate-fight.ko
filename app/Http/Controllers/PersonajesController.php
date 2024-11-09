<?php

namespace App\Http\Controllers;

use App\Models\Personaje;
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
     *     path="/personajes",
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *     path="/personajes",
     *     tags={"Personajes"},
     *     summary="Crear un nuevo personaje",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Personaje")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Personaje creado con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Personaje")
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Personaje::$rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $personaje = Personaje::create($request->all());
        return response()->json(['personaje' => $personaje], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/personajes/{id}",
     *     tags={"Personajes"},
     *     summary="Obtener un personaje específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del personaje",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Personaje encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Personaje")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Personaje no encontrado"
     *     )
     * )
     */

    public function show($id)
    {
        $personaje = Personaje::find($id);
        if (!$personaje) {
            return response()->json(['error' => 'Personaje no encontrado'], 404);
        }
        return response()->json(['personaje' => $personaje], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *     path="/personajes/{id}",
     *     tags={"Personajes"},
     *     summary="Actualizar un personaje existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del personaje",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Personaje")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Personaje actualizado con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/Personaje")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Personaje no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $personaje = Personaje::find($id);
        if (!$personaje) {
            return response()->json(['error' => 'Personaje no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), Personaje::$rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $personaje->update($request->all());
        return response()->json(['personaje' => $personaje], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     *
     * @OA\Delete(
     *     path="/personajes/{id}",
     *     tags={"Personajes"},
     *     summary="Eliminar un personaje",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del personaje",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Personaje eliminado con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Personaje no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $personaje = Personaje::find($id);
        if (!$personaje) {
            return response()->json(['error' => 'Personaje no encontrado'], 404);
        }

        $personaje->delete();
        return response()->json(null, 204);
    }
}
