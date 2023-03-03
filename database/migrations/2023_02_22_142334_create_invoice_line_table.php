<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_line', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('concept');
            $table->float('base');
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('service_type_id')->constrained('service_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_line');
    }
}
