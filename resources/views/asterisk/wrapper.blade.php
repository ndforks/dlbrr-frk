<!DOCTYPE html>
<html>
<head>
    <title>Asterisk redirection from Dolibarr...</title>
</head>
@if($dialStatus === 'success')
    <body onload="history.go(-1);">
@else
    <body>
@endif
    @if($error)
        <div class="error">
            {!! $error !!}
        </div>
    @elseif($dialStatus === 'success')
        <!-- Call initiated successfully for caller: {{ $caller }}, called: {{ $called }} -->
    @endif
</body>
</html>
