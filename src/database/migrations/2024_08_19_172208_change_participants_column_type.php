<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeParticipantsColumnType extends Migration
{
    public function up()
    {
        Schema::table('vacation_plans', function (Blueprint $table) {
            // Alterar o tipo da coluna 'participants' para JSON
            $table->json('participants')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('vacation_plans', function (Blueprint $table) {
            // Reverter a alteração se necessário
            $table->longText('participants')->nullable()->change();
        });
    }
}

