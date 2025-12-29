<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reader_id')->constrained()->cascadeOnDelete();
            $table->json('details')->nullable();
            $table->timestamps();
            $table->unique(['reader_id', 'book_id', 'returned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
}