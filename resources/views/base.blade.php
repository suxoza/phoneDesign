<!DOCTYPE html>
<html>
<head>
    <title>Phone</title>
    <meta charset="utf-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link href="{{ mix('/css/style.css') }}" rel="stylesheet" />
</head>
<body>


@yield('content')

<script>window._selfKey = '{{ csrf_token() }}';</script>
<script type="text/javascript" src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>