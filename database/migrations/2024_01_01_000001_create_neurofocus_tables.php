<?php
// PATH: database/migrations/2024_01_01_000001_create_neurofocus_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // ADD COLUMNS TO EXISTING USERS TABLE
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('password');
            $table->enum('mode', ['focus', 'moving_on', 'sleep', 'meditation'])
                  ->default('focus')->after('age');
        });

        // SENSOR READINGS TABLE
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->float('heart_rate');
            $table->float('spo2')->default(98);
            $table->float('ecg_signal');
            $table->float('focus_score');
            $table->boolean('is_distracted')->default(false);
            $table->string('distraction_type')->nullable();
            $table->timestamps();
        });

        // FOCUS SESSIONS TABLE
        Schema::create('focus_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('total_seconds')->default(0);
            $table->integer('focused_seconds')->default(0);
            $table->integer('distracted_seconds')->default(0);
            $table->float('avg_focus_score')->default(0);
            $table->float('avg_heart_rate')->default(0);
            $table->timestamps();
        });

        // DISTRACTION LOGS TABLE
        Schema::create('distraction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('trigger_type');
            $table->string('app_name')->nullable();
            $table->float('heart_rate_at_trigger');
            $table->integer('duration_seconds')->default(0);
            $table->timestamp('detected_at');
            $table->timestamps();
        });

        // AI INSIGHTS TABLE
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('insight_type');
            $table->text('message');
            $table->string('peak_focus_time')->nullable();
            $table->float('recovery_score')->default(0);
            $table->json('pattern_data')->nullable();
            $table->timestamps();
        });

        // DAILY REPORTS TABLE
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('report_date');
            $table->float('avg_focus_score');
            $table->float('avg_heart_rate');
            $table->integer('total_focused_mins');
            $table->integer('total_distracted_mins');
            $table->integer('distraction_count');
            $table->float('efficiency_percent');
            $table->integer('emotional_spikes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('daily_reports');
        Schema::dropIfExists('ai_insights');
        Schema::dropIfExists('distraction_logs');
        Schema::dropIfExists('focus_sessions');
        Schema::dropIfExists('sensor_readings');

        // Remove added columns from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'mode']);
        });
    }
};