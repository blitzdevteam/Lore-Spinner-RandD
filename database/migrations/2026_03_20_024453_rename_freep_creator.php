<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('creators')
            ->where('email', 'freep@lorespinner.com')
            ->update(['first_name' => 'FREEP1']);
    }

    public function down(): void
    {
        DB::table('creators')
            ->where('email', 'freep@lorespinner.com')
            ->update(['first_name' => 'FREEP!']);
    }
};
