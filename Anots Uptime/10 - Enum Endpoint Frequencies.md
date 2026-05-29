1 - com a criaçao do enum dei rollback o frequency recebe 0

   <!-- 
use App\Enums\EndpointFrequency; ##### aqui
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
        Schema::create('endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('location'); 
            $table->unsignedBigInteger('frequency')->default(EndpointFrequency::FIVE_MINUTES->value); ############## aqui
            $table->timestamp('next_check'); // now + frequency
            $table->timestamps();
        });
    }
 -->


<!-- 2  na raiz do projecto crei o enum EndpointFrequency -->
<?php

namespace App\Enums;

enum EndpointFrequency: int
{
    case ONE_MINUTE = 1 * 60;

    case FIVE_MINUTES = 5 * 60;

    case THIRTY_MINUTES = 30 * 60;
    
    case ONE_HOUR = 60 * 60;

    
}



3 - rodo o migrate 