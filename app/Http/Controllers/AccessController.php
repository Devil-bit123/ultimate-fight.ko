<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Post(
     *     path="/api/usuarios",
     *     tags={"Usuarios"},
     *     summary="Crear un nuevo usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *             @OA\Property(property="password", type="string", example="contraseñaSegura"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-30T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-30T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {

        // Obtener y validar los datos de la solicitud
        $validator = Validator::make($request->all(), User::$rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Hashear la contraseña antes de crear el usuario
        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));

        // Crear el usuario con la contraseña hasheada
        $user = User::create($data);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {

        // Buscar el usuario por nombre
        $user = User::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Generar el token usando Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        // Mensaje de error si las credenciales no coinciden
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        // Eliminar el token de la sesión
        //$request->session()->invalidate();
        $user = User::find($request->id);
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
