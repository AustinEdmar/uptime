docker-compose run --rm artisan make:model Endpoint -m


<!-- migration

 public function up(): void
    {
        Schema::create('endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('location');
            $table->unsignedBigInteger('frequency')->default(60);
            $table->timestamps();
        });
    }
    
     -->


<!-- 2 - model site -->

public function endpoints()
    {
        return $this->hasMany(Endpoint::class);
    }

<!-- 3 - model endpoint -->

public function site()
    {
        return $this->belongsTo(Site::class);
    }

<!-- rodei  -->
docker-compose run --rm artisan migrate:rollback 
porque esqueci 

 Schema::create('endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('location');
            $table->unsignedBigInteger('frequency')->default(60);
            $table->timestamp('next_check'); // now + frequency
            $table->timestamps();
        });

<!-- 4 - model endpoint -->
class Endpoint extends Model
{
    protected $fillable = [
        'location',
        'frequency',
        'next_check',
    ];

     public $dates = [ vou usar no carbon
        'next_check',
    ];