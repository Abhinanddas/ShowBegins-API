<?php

namespace Database\Seeders;

use App\Models\Screen;
use Illuminate\Database\Seeder;

class ScreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'SB Audi One',
                'seating_capacity' => 1000
            ],
            [
                'id' => 2,
                'name' => 'SB Audi Two',
                'seating_capacity' => 500,
            ],
            [
                'id' => 3,
                'name' => 'SB IMAX',
                'seating_capacity' => 500,
            ],
            [
                'id' => 4,
                'name' => 'SB Audi Three',
                'seating_capacity' => 200,
            ],
        ];
        Screen::upsert($data, ['id']);
    }
}
