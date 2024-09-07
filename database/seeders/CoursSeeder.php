<?php

namespace Database\Seeders;

use App\Models\Cours;
use Illuminate\Database\Seeder;

class CoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cours::factory()
            ->count(3)
            ->create();

    }
}
