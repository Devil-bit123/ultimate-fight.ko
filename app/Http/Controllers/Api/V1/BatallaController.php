<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Sala;
use App\Models\Personaje;

use Illuminate\Http\Request;
use App\Models\PersonajeSala;
use App\Models\RegistroDeAtaque;
use Faker\Provider\ar_EG\Person;
use App\Http\Controllers\Controller;




class BatallaController extends Controller
{

    /**
     * @OA\Post(
     *     path="api/v1/salas/{uuid}/atacar",
     *     summary="Realiza un ataque a otro jugador en la sala.",
     *     description="Este endpoint permite que un jugador ataque a otro en una sala de juego. Se valida que el jugador no ataque a sí mismo, se verifica el estado de la sala, y se calcula el daño basado en la habilidad utilizada.",
     *     operationId="atacar",
     *     tags={"Ataques"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para realizar el ataque",
     *         @OA\JsonContent(
     *             required={"sala_id", "jugador_atacante_id", "jugador_objetivo_id", "habilidad"},
     *             @OA\Property(property="sala_id", type="integer", description="ID de la sala en la que se realiza el ataque"),
     *             @OA\Property(property="jugador_atacante_id", type="integer", description="ID del jugador que realiza el ataque"),
     *             @OA\Property(property="jugador_objetivo_id", type="integer", description="ID del jugador que será atacado"),
     *             @OA\Property(property="habilidad", type="string", description="Nombre de la habilidad utilizada en el ataque (ej. 'puño_derecho')"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El ataque fue exitoso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ataque exitoso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en los datos proporcionados o el ataque falló.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El objetivo ha esquivado el ataque.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La sala no fue encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sala no encontrada.")
     *         )
     *     )
     * )
     */

    public function atacar(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'sala_id' => 'required|exists:salas,id',
            'jugador_atacante_id' => 'required|exists:users,id',
            'jugador_objetivo_id' => 'required|exists:users,id',
            'habilidad' => 'required|string',  // Nombre de la habilidad (ej. 'puño_derecho')
        ]);

        if ($request->jugador_atacante_id == $request->jugador_objetivo_id) {
            return response()->json(['message' => 'El jugador no puede atacar a si mismo.'], 400);
        }

        // Obtener el personaje atacante y el objetivo en la sala correspondiente
        $juego = PersonajeSala::where('sala_id', $request->sala_id)->get();
        $sala = Sala::find($request->sala_id);
        if (!$juego) {
            return response()->json(['message' => 'Sala no encontrada.'], 404);
        }

        if ($sala->estado == 'terminada') {
            $ganador = $juego->where('vida_personaje', '!=', 0)->first();
            //devuelve el jugador ganador
            return response()->json(['message' => 'El jugador ' . $ganador->jugador_id . ' ha ganado.'], 200);
        }

        //dd($juego);
        $personajeAtacante = $juego->where('jugador_id', $request->jugador_atacante_id)->first();
        $personajeObjetivo = $juego->where('jugador_id', $request->jugador_objetivo_id)->first();
        //dd($personajeAtacante);
        //dd($personajeObjetivo);
        // Busca al personaje atacante
        $personaje = Personaje::find($personajeAtacante->personaje_id);
        $habilidades = $personaje->habilidades;
        // Busca la habilidad específica en el array de habilidades
        $dañoHabilidad = $habilidades[$request->habilidad];

        // Muestra el resultado
        //dd($dañoHabilidad);

        if ($dañoHabilidad < $personajeObjetivo->miss_percent) {
            return response()->json(['message' => 'El objetivo a esquivado el ataque.'], 400);
        }

        if ($dañoHabilidad > $personajeObjetivo->vida_personaje) {
            //dd('daño'.$dañoHabilidad .'vida'. $personajeObjetivo->vida_personaje);
            $personajeObjetivo->vida_personaje = 0;
            $sala->estado = 'terminada';
            $sala->save();
            $personajeObjetivo->save();
            return response()->json(['message' => 'El objetivo a muerto.'], 400);
        }

        $personajeObjetivo->vida_personaje -= $dañoHabilidad;
        //dd('Daño'.$dañoHabilidad.' aplicado exitosamente. Vida restante: ' . $personajeObjetivo->vida_personaje);
        //guardar el daño aplicado
        $personajeObjetivo->save();

        //registro de ataque
        RegistroDeAtaque::create([
            'sala_id' => $request->sala_id,
            'jugador_atacante_id' => $request->jugador_atacante_id,
            'jugador_defensor_id' => $request->jugador_objetivo_id,
            'daño' => $dañoHabilidad,
            'resultado' => 'exitoso',
        ]);

        return response()->json(['message' => 'Ataque exitoso.'], 200);
    }
}
