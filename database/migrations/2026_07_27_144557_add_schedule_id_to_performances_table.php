<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('setlist_id')->constrained()->nullOnDelete();
        });
        
        Schema::table('song_requests', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('gig_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });
        
        Schema::table('song_requests', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });
    }
};
