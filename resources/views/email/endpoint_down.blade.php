@component('mail::message')
# {{ $endpoint->site->name }} - Endpoint Down

O endpoint **{{ $endpoint->location }}** está fora do ar.

**Status Code:** {{ $endpoint->checks()->latest()->first()?->response_code }}

@component('mail::button', ['url' => config('app.url')])
Ver Detalhes
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent