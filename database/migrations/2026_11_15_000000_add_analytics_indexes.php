<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['gymkos_id', 'status', 'created_at'], 'trx_analytics_idx');
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->index(['gymkos_id', 'type', 'date'], 'fin_analytics_idx');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->index(['gymkos_id', 'join_date'], 'members_join_idx');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', fn (Blueprint $t) => $t->dropIndex('trx_analytics_idx'));
        Schema::table('finances', fn (Blueprint $t) => $t->dropIndex('fin_analytics_idx'));
        Schema::table('members', fn (Blueprint $t) => $t->dropIndex('members_join_idx'));
    }
};