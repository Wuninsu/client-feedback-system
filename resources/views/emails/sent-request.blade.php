<x-mail::message>
    # Hello {{ $clientName }},

    {{ $messageContent }}

    <x-mail::button :url="$feedbackLink">
        Give Feedback
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
