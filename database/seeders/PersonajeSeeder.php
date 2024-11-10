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
                'puño_derecho'=>$faker->numberBetween(30, 50),
                'puño_izquierdo'=>$faker->numberBetween(30, 50),
                'patada_derecha'=>$faker->numberBetween(30, 50),
                'patada_izquierda'=>$faker->numberBetween(30, 50),
                'ataque_especial'=>$faker->numberBetween(30, 80),
            ];

            Personaje::create([
                'nombre' => $faker->name,
                'vida' => $faker->numberBetween(300, 350),
                'miss_percent' => $faker->numberBetween(15, 35),
                'habilidades' => $habilidades,
            ]);
        }

    }
}
