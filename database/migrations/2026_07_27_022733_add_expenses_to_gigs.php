<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->decimal('transport', 10, 2)->default(0)->after('tips');
            $table->decimal('parking', 10, 2)->default(0)->after('transport');
            $table->decimal('food', 10, 2)->default(0)->after('parking');
            $table->decimal('equipment_rental', 10, 2)->default(0)->after('food');
            $table->decimal('other_expenses', 10, 2)->default(0)->after('equipment_rental');
        });
    }

    public function down(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropColumn(['transport', 'parking', 'food', 'equipment_rental', 'other_expenses']);
        });
    }
};
