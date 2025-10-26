<x-mail::message>
    # Hello {{ $clientName }},

    We value your opinion and would love to hear your thoughts on our
    product{{ count($feedback->products) > 1 ? 's' : '' }}.

    <x-mail::button :url="$link">
        Give Feedback
    </x-mail::button>

    {{ $messageContent }}

    If the button doesn't work, you can also click or copy this link:
    [{{ $link }}]({{ $link }})

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>