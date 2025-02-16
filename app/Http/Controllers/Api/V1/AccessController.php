<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", example="juan.perez@correo.com")
 * )
 */

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
     *     path="/api/v1/usuarios",
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

    /**
     * @OA\Post(
     *     path="api/v1/login",
     *     summary="Inicia sesión de un usuario y devuelve un token de autenticación.",
     *     description="Este endpoint permite que un usuario inicie sesión proporcionando su nombre de usuario y contraseña. Si las credenciales son correctas, se genera un token de autenticación usando Sanctum y se devuelve junto con los detalles del usuario.",
     *     operationId="login",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciales de inicio de sesión",
     *         @OA\JsonContent(
     *             required={"name", "password"},
     *             @OA\Property(property="name", type="string", description="Nombre de usuario del usuario"),
     *             @OA\Property(property="password", type="string", description="Contraseña del usuario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso y token generado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", description="Token de autenticación generado para el usuario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */

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


    /**
     * @OA\Post(
     *     path="api/v1/logout",
     *     summary="Cerrar sesión de un usuario y eliminar el token de autenticación.",
     *     description="Este endpoint permite que un usuario cierre sesión eliminando su token de autenticación, lo que invalida su acceso posterior hasta que se vuelva a generar un nuevo token.",
     *     operationId="logout",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="ID del usuario que desea cerrar sesión",
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", description="ID del usuario que está cerrando sesión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud incorrecta, no se proporcionó un ID de usuario válido.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid user ID")
     *         )
     *     )
     * )
     */

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
