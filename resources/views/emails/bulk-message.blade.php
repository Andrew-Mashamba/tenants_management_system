<x-mail::message>
# {{ $message->subject }}

{{ $message->content }}

<x-mail::button :url="config('app.url')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> 