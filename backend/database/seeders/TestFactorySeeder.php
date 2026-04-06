<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestFactorySeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(1)->create();
    }
}
