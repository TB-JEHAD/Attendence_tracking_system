<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // drop the old student_id FK & column
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');

            // add user_id FK to users
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // drop user_id and its FK
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // restore student_id FK
            $table->foreignId('student_id')
                ->after('id')
                ->constrained('students')
                ->onDelete('cascade');
        });
    }
};
