<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained('complaints')->cascadeOnDelete();

            // Internal staff reply (nullable when reply comes from the client/guest)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('author_name')->nullable();  // used when reply comes from client/guest
            $table->string('author_email')->nullable();

            $table->text('message');
            $table->boolean('is_internal_note')->default(false); // internal note not visible to client

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_replies');
    }
};
