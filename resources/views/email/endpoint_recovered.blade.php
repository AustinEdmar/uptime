@component('mail::message')
# Recuperado o {{ $endpoint->site->domain }}

O endpoint **{{ $endpoint->location }}** Voltou ao ar.

**Status Code:** {{ $endpoint->checks()->latest()->first()?->response_code }}

@component('mail::button', ['url' => config('app.url')])
Ver Detalhes
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent