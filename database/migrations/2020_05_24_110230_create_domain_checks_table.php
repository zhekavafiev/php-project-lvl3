<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('domain_id');
            $table->foreign('domain_id')->references('id')->on('domains');
            $table->bigInteger('status_code');
            $table->string('keywords');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain_checks');
    }
}
