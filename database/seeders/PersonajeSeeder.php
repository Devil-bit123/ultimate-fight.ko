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
            Personaje::create([
                'nombre' => $faker->name,
                'vida' => $faker->numberBetween(130, 150),
                'ataque' => $faker->numberBetween(25, 45),
                'defensa' => $faker->numberBetween(20, 50),
                'velocidad' => $faker->numberBetween(70, 100),
                'habilidades' => json_encode([$faker->word, $faker->word])
            ]);
        }

    }
}
