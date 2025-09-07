<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // 1) drop the old unique key (on date only or old columns)
            $table->dropUnique('attendances_student_id_date_unique');
            // If you see a different name in your error, drop that instead:
            // $table->dropUnique('attendances_date_unique');

            // 2) add a composite unique index on the real FK + date
            $table->unique(['user_id', 'date'], 'attendances_user_id_date_unique');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // drop the new composite index
            $table->dropUnique('attendances_user_id_date_unique');

            // restore the old unique key if you need to
            $table->unique('date', 'attendances_student_id_date_unique');
        });
    }
};
