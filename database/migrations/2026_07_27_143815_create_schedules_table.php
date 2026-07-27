<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('rehearsal'); // 'rehearsal' | 'gig'
            $table->string('title');
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('status')->default('planned'); // planned | confirmed | completed | cancelled
            $table->text('description')->nullable();

            // Rehearsal fields
            $table->string('location')->nullable();

            // Gig fields
            $table->string('venue')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();

            // Financial fields (gig only)
            $table->decimal('payment', 12, 2)->default(0);
            $table->decimal('tips', 12, 2)->default(0);
            $table->decimal('transport', 12, 2)->default(0);
            $table->decimal('parking', 12, 2)->default(0);
            $table->decimal('food', 12, 2)->default(0);
            $table->decimal('equipment_rental', 12, 2)->default(0);
            $table->decimal('other_expenses', 12, 2)->default(0);

            // Setlist (created inline when creating schedule)
            $table->foreignId('setlist_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
