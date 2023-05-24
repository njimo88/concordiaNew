<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f6f6f6;
            color: #333;
        }

        .kr-embedded {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
    </style>

    <script type="text/javascript"
        src="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js" 
        kr-public-key="{{ env('API_PUBLIC_KEY') }}"
        kr-get-url-success="{{ route('detail_paiement', ['id' => 1, 'nombre_cheques' => 1]) }}";>
    </script>

    <link rel="stylesheet" href="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/ext/neon-reset.css">
    <script type="text/javascript" src="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/ext/neon.js"></script>
</head>

<body>
    <div class="kr-embedded" kr-form-token="{{ $formToken }}">
    </div>
</body>
</html>
