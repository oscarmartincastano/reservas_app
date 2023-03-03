<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('number');
            $table->date('date');

            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('bank_id')->constrained('banks');
            $table->integer('instalacion_id')->constrained('instalaciones');

            $table->float('paid')->default(0);
            $table->date('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
