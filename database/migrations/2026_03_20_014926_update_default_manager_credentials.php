<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('managers')
            ->where('email', 'a@a.com')
            ->update([
                'email' => env('MANAGER_EMAIL', 'admin@lorespinner.com'),
                'password' => bcrypt(env('MANAGER_PASSWORD', 'Lr$p1n#2026!Mgr')),
            ]);
    }

    public function down(): void
    {
        DB::table('managers')
            ->where('email', env('MANAGER_EMAIL', 'admin@lorespinner.com'))
            ->update([
                'email' => 'a@a.com',
                'password' => bcrypt('password'),
            ]);
    }
};
