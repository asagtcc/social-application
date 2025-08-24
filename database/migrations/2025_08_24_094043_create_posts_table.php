<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('social_account_id') ->constrained() ->onDelete('cascade');
            $table->enum('type', ['post', 'reel', 'story'])->default('post');
            $table->text('content')->nullable();
            $table->string('external_post_id')->nullable();
            $table->enum('status', ['queue', 'drafts', 'approvals','sent', 'failed'])->default('queue');
            $table->timestamp('published_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
