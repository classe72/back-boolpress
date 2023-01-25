<h1>Ciao admin!</h1>
<p>
    Hai un nuovo messaggio!!<br>
    Ecco i dettagli:<br>

    Nome: {{ $lead->name }}<br>
    Email: {{ $lead->email }}<br>
    Messaggio:<br>
    {{ $lead->message }}
</p>