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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cpf')->unique();
            $table->enum('association', ['solidy', 'nova'])->default('solidy');
            $table->enum('status', ['active', 'accepted', 'completed-lessons', 'refused-by-criminal-history', 'refused-on-test'])->default('active');
            $table->integer('ileva_team_id')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth');
            $table->timestamp('date_of_the_test')->nullable();
            $table->string('address');
            $table->string('number');
            $table->string('cep');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
