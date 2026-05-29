1 - o Laravel tem um wrapper http do gazlle que permite fazer request de forma simples

2c- vamos na model Endpoint add relacionamento 
 
 <!-- 1️⃣ O que é esse método?
public function url()


É um método do model Endpoint.

Sempre que você chama $endpoint->url(), ele executa esse código.

O objetivo é retornar a URL completa do endpoint, juntando o site e o caminho específico do endpoint.

2️⃣ O $this

Dentro do método, $this significa o objeto Endpoint atual.

Exemplo:

$endpoint = Endpoint::find(1);
$endpoint->url(); // aqui $this é $endpoint

3️⃣ $this->site

$this->site vem do método site():

public function site()
{
    return $this->belongsTo(Site::class);
}


$this->site retorna o Site associado a este Endpoint (objeto do tipo Site).

Laravel faz isso automaticamente usando a chave site_id do Endpoint.

Exemplo:

$endpoint->site; // retorna Site { id: 1, domain: "example.com" }

4️⃣ $this->site->url()

$this->site->url() chama o método url() do Site.

Esse método normalmente retorna a URL base do site.
Exemplo:

public function url()
{
    return 'https://' . $this->domain;
} -->
 
    public function url()
    {
        return $this->site->url() . $this->location;
    }


3 - no job PerformEndpointCheck.php vamos fazer o request
  public function handle(): void
    {
        try{

            $response = Http::get($this->endpoint->url());

            dd($response->status());
        } catch(\Exception $e){
            //log error
        }

<!-- lmbrando o command feaz o for e job executa -->
docker-compose run --rm artisan checks:perform