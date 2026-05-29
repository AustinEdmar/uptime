docker-compose run --rm artisan make:model Site -m

<!-- 1 - a migration -->
  */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('scheme');
            $table->string('domain');
            $table->string('default')->default(0);

            $table->timestamps();
        });
    }


    <!-- 2 vamos na model -->

    <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Site extends Model
{
    
    protected $fillable = [
       
        'scheme',
        'domain',
        'default',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


<!-- 3 vamos criar controllers -->
docker-compose run --rm artisan make:controller DashboardController

class DashboardController extends Controller
{
    public function __invoke(Request $request, Site $site){

        return Inertia::render('Dashboard', [
            'sites' => $site,
        ]);
    }
}

<!-- 4 rota  -->
Route::get('/dashboard/{site?}', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
<!-- 5 vamos criar a view -->
resources\js\Pages\Dashboard.vue

import { Head } from '@inertiajs/vue3';

defineProps({
    sites: Object,
});
</script>

<template>
    <Head title="Dashboard" />
    
    <div>
        {{ sites }}
    </div>
</template>




<!--  -->