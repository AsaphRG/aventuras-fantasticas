<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LuckTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('db_backups/luck_tests.sql');
        if (File::exists($path)) {
            $sql = File::get($path);
            DB::unprepared($sql);
        }
    }
}
