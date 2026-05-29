1 - adicionar coluna php artisan make:migration add_notification_emails_to_site_table na tabela site

<!-- <?php

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
        Schema::table('sites', function (Blueprint $table) {
           $table->json('notification_emails')->nullable()->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('notification_emails');
        });
    }
};
 -->

2 - php artisan migrate

<!-- 3 na model -->
  protected $fillable = [
        'scheme',
        'domain',
        'default',
        'notification_emails',
    ];


    protected $casts = [
        'default' => 'boolean',
        'notification_emails' => 'array',
    ];

















