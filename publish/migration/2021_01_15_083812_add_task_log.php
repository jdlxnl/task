<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('step');
            $table->string('status');
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('task_log_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_log_id')
                ->constrained('task_logs');
            $table->integer('entry_number')->default(0);
            $table->string('severity');
            $table->text('message');
            $table->json('context')->nullable();
            $table->timestamps(3);
        });

        Schema::create('task_logs_relations', function (Blueprint $table) {
            $table->foreignId('task_log_id')
                ->constrained('task_logs');
            $table->string('task_logs_relation_id');
            $table->string('task_logs_relation_type');
            $table->timestamps();
        });

        Schema::table('task_logs', function (Blueprint $table) {
            $table->index(['type']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**git
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_logs');
        Schema::dropIfExists('task_log_entries');
        Schema::dropIfExists('task_logs_relations');
    }
}
