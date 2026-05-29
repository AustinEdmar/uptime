<!-- 1 - agora no job testamos e funcionou  -->

    public function handle(): void
    {
        try{

            $response = Http::get($this->endpoint->url());

            

        } catch(\Exception $e){
           
        }



        //vamos criar um check
        $this->endpoint->checks()->create([
            'response_code' => $response->status(),
            'response_body' => !$response->successful() ? $response->body() : null,
        ]);