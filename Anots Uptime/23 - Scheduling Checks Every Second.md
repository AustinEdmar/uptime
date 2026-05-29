// vai 
1 - docker-compose run --rm composer require spatie/laravel-short-schedule

route/console
use Illuminate\Support\Facades\Schedule;
ShortSchedule::command('checks:perform')->everySecond(); // o checks:perform é do command


<!-- rodo -->
docker-compose run --rm artisan short-schedule:run


<!-- no job coloquei -->

 public function handle(): void
    {
        try{

            $response = Http::get($this->endpoint->url());

            Log::info($response->status());
            
        } catch(\Exception $e){
            Log::error($e->getMessage());
        }





