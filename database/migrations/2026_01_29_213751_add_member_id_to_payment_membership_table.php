<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_membership', function (Blueprint $table) {
            // Kita buat nullable karena Member BARU (register) belum punya ID
            // Kita taruh setelah order_id biar rapi
            $table->foreignId('member_id')->nullable()->after('order_id')->constrained('members')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('payment_membership', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropColumn('member_id');
        });
    }
};
