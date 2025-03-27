<?php

use App\Models\Playlist;
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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playlist::class)->constrained()->cascadeOnDelete();
            // from youtube API
            $table->string('video_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('published_at');
            $table->json('thumbnails');
            $table->integer('position');
            $table->json('tags')->nullable();
            $table->json("content_details");
            $table->json("statistics");
            $table->json("channel");
            $table->json("status");
            $table->json("player");

            // Additional fields
            $table->boolean('is_completed')->default(false);
            $table->integer('progress')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
