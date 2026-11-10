<x-mail::message>
# Nouveau message

Bonjour **{{ $portfolioName }}**,

Vous avez reçu un nouveau message via votre portfolio.

<x-mail::panel>
**Expéditeur :** {{ $senderName }}  
**Email :** [{{ $senderEmail }}](mailto:{{ $senderEmail }})
</x-mail::panel>

**Message :**

{!! nl2br(e($body)) !!}

<x-mail::button :url="'mailto:' . $senderEmail . '?subject=Re: Votre message'">
Répondre à {{ $senderName }}
</x-mail::button>

Merci,<br>
L'équipe NEXORA
</x-mail::message>