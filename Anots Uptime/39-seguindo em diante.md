1 - php artisan make:event EndpointWentDown

<!-- registro no service provider -->
<?php

namespace App\Providers;

use App\Models\Endpoint;
use App\Models\Site;
use App\Models\Check;
use App\Observers\EndpointObserver;
use App\Observers\SiteObserver;
use App\Observers\CheckObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Policies\SitePolicy;
use App\Policies\EndpointPolicy;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\EndpointWentDown;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Site::observe(SiteObserver::class);
        Endpoint::observe(EndpointObserver::class);
        Check::observe(CheckObserver::class); // esta observando a model Check


        Gate::define('delete-endpoint', [EndpointPolicy::class, 'delete']);
        
        Gate::define('update-endpoint', [EndpointPolicy::class, 'update']);

        Gate::define('view-endpoint', [EndpointPolicy::class, 'view']);
       
        Carbon::setLocale('pt');

         Event::listen(
        EndpointWentDown::class,
        SendDownEmailNotifications::class
        );
    }
}


<!-- 2 - crie o listener -->
php artisan make:listener SendDownEmailNotifications

<!-- 3 vou no CheckObserver disparar os checks -->

<?php

namespace App\Observers;

use App\Models\Check;
use App\Events\EndpointWentDown;

class CheckObserver
{
    //// Evita alertas repetidos: só entra quando o site cai pela primeira vez
    public function created(Check $check): void
    {   
        if (
            !$check->isSuccessful() &&
            ($check->previous()?->isSuccessful() || $check->endpoint->checks()->count() === 1)
            ) {
            EndpointWentDown::dispatch($check);
        }
    }   
   
}


<!-- 4 no evento EndpointWentDown -->
<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Check;

class EndpointWentDown
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Check $check)
    {
        //
    }

}


<!-- 5 no listener SendDownEmailNotifications -->
public function handle(object $event): void
    {
        collect($event->check->endpoint->site->notification_emails)->each(function ($email) use ($event) {
            // send email
           // Mail::to($email)->send(new EndpointDown($event->check));
        });
    }
<!-- 6 criar o  php artisan make:notification EndpointDownNotification -->

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Endpoint;

class EndpointDownNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Endpoint $endpoint) ###### aqui ele pega o endpoint
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->endpoint->site->name . ' Endpoint Down') ######## aqui
            ->markdown('email.endpoint_down', [
                'endpoint' => $this->endpoint,
            ]);
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}


<!-- no blade  -->
Endpoint Down {{ $endpoint->id }} when down {{ $endpoint->created_at }}


<!-- 7 volto no listener -->
 public function handle(object $event): void
    {
        collect($event->check->endpoint->site->notification_emails)->each(function ($email) use ($event) {
            Notification::route('email', $email)->notify(new EndpointDownNotification($event->check->endpoint));
        });
    }














