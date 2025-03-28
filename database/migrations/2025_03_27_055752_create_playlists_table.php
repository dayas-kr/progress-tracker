<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            // from youtube API
            $table->string('playlist_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->json('channel_images')->nullable();
            $table->string('channel_id');
            $table->string('channel_title');
            $table->integer('video_count')->default(0);
            $table->bigInteger('subscriber_count')->nullable();

            // Additional fields
            $table->integer('total_duration')->nullable(); // In seconds
            $table->integer('average_duration')->nullable(); // In seconds
            $table->integer('progress')->default(0);
            $table->boolean('watched')->default(false);

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
