<?php

namespace Database\Seeders;

use App\Models\Personaje;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PersonajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {

            $habilidades = [
                'puño_derecho'=>$faker->numberBetween(20, 50),
                'puño_izquierdo'=>$faker->numberBetween(20, 50),
                'patada_derecha'=>$faker->numberBetween(20, 50),
                'patada_izquierda'=>$faker->numberBetween(20, 50),
                'ataque_especial'=>$faker->numberBetween(50, 80),
            ];

            Personaje::create([
                'nombre' => $faker->name,
                'vida' => $faker->numberBetween(130, 150),
                'ataque' => $faker->numberBetween(25, 45),
                'defensa' => $faker->numberBetween(20, 50),
                'velocidad' => $faker->numberBetween(70, 100),
                'habilidades' => $habilidades,
            ]);
        }

    }
}
