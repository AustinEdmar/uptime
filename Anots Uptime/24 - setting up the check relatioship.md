1 - para cada verificaçao queremos armazenar por isso vamos criar um model check

docker-compose run --rm artisan make:model Check -m


<!-- 1 migration -->


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endpoint_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('response_code');
            $table->text('response_body')->nullable();
            $table->timestamps();
        });
    }

docker-compose run --rm artisan migrate

<!-- 2n - model -->
class Check extends Model
{
    protected $fillable = [
        
        'response_code',
        'response_body',
    ];


    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }

     public function check() // add este 
    {
        return $this->hasOne(Check::class)->latestOfMany();
    }
}

<!-- 3 na model Endpoint -->

 public function checks()
    {
        return $this->hasMany(Check::class);
    }














