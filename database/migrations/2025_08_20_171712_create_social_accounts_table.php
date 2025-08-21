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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')
                    ->constrained()
                    ->onDelete('cascade'); 

            $table->foreignId('social_network_id')
                    ->constrained()
                    ->onDelete('cascade'); 

            $table->string('account_name')->nullable(); 
            $table->string('account_id')->nullable();

            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['active','expired','revoked'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
