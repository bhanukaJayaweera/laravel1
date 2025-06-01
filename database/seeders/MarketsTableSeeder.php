<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketsTableSeeder extends Seeder
{
    public function run()
    {
        $markets = [
            ['name' => 'Pettah Market', 'location' => 'Pettah', 'district' => 'Colombo'],
            ['name' => 'Nugegoda Market', 'location' => 'Nugegoda', 'district' => 'Colombo'],
            ['name' => 'Dambulla Dedicated Economic Centre', 'location' => 'Dambulla', 'district' => 'Matale'],
            ['name' => 'Kandy Market', 'location' => 'Kandy', 'district' => 'Kandy'],
            ['name' => 'Galle Market', 'location' => 'Galle', 'district' => 'Galle'],
            ['name' => 'Kurunegala Market', 'location' => 'Kurunegala', 'district' => 'Kurunegala'],
            ['name' => 'Jaffna Market', 'location' => 'Jaffna', 'district' => 'Jaffna'],
        ];

        foreach ($markets as $market) {
            Market::create($market);
        }
    }
}