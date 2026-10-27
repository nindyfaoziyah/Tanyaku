<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('datasets', 'file_path')) {
            Schema::table('datasets', function (Blueprint $table) {
                $table->string('file_path')
                    ->nullable()
                    ->after('original_filename');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('datasets', 'file_path')) {
            Schema::table('datasets', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }
};