@component('mail::message')
# Falha ao monitorar o {{ $endpoint->site->domain }}

O endpoint **{{ $endpoint->location }}** está fora do ar.

**Status Code:** {{ $endpoint->checks()->latest()->first()?->response_code }}

@component('mail::button', ['url' => config('app.url')])
Ver Detalhes
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent