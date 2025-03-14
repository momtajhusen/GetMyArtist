<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'title' => 'Campus',
                'description' => 'A technology conference for developers and tech enthusiasts.',
                'event_date' => Carbon::now()->addDays(15),
                'location' => 'Kathmandu Convention Center',
                'created_by' => 9,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Charity',
                'description' => 'Networking event for entrepreneurs and startups.',
                'event_date' => Carbon::now()->addDays(25),
                'location' => 'Lalitpur Innovation Hub',
                'created_by' => 9,  
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Concert/Festival',
                'description' => 'An open-air music festival featuring various artists.',
                'event_date' => Carbon::now()->addDays(30),
                'location' => 'Pokhara Lake Side',
                'created_by' => 9, 
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
