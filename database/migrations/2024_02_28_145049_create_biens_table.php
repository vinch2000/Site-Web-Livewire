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
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('sold')->default(0);
            $table->string('lib', 80)->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('prix', 24, 6)->nullable();
            $table->string('photo', 50)->nullable();
            $table->string('classe_energie', 5)->nullable();
            $table->smallInteger('chambre')->nullable();
            $table->smallInteger('sdb')->nullable();
            $table->smallInteger('wc')->nullable();
            $table->integer('st')->nullable();
            $table->integer('sh')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('type_bien_id')->nullable();
            $table->foreign('type_bien_id')->references('id')->on('type_biens');

            $table->unsignedBigInteger('type_annonce_id')->nullable();
            $table->foreign('type_annonce_id')->references('id')->on('type_annonces');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
