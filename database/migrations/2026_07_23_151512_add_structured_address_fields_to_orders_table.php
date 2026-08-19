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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_country')->nullable()->after('shipping_address');
            $table->string('customer_state')->nullable()->after('customer_country');
            $table->string('customer_city')->nullable()->after('customer_state');
            $table->string('customer_street')->nullable()->after('customer_city');

            $table->boolean('ships_to_customer_address')->default(true)->after('customer_street');

            $table->string('shipping_country')->nullable()->after('ships_to_customer_address');
            $table->string('shipping_state')->nullable()->after('shipping_country');
            $table->string('shipping_city')->nullable()->after('shipping_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_country', 'customer_state', 'customer_city', 'customer_street',
                'ships_to_customer_address', 'shipping_country', 'shipping_state', 'shipping_city',
            ]);
        });
    }
};
