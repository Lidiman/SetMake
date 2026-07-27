<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->string('status')->default('completed')->after('venue');
            $table->foreignId('gig_id')->nullable()->after('setlist_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->dropForeign(['gig_id']);
            $table->dropColumn(['status', 'gig_id']);
        });
    }
};
