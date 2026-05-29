1 - vamos normalizar para o user nao enviar ex ///abc?=true??!

vamos no observer, adicione no model site

<!-- public function url()
    {
         return $this->scheme . '://' . $this->domain;
    } -->

volto no obsever


<!--  public function creating(Endpoint $endpoint)
    {

       
        $parsed = parse_url($endpoint->site->url() . '/' . $endpoint->location);

        $endpoint->location = '/' . trim(trim(Arr::get($parsed, 'path'), '/') . '?' . Arr::get($parsed, 'query'), '?');
        
        $endpoint->next_check = now()->addSeconds($endpoint->frequency);
    } -->