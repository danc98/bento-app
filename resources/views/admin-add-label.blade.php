<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Add a Label</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>
            header {
                display: flex;
                align-items: center;
            }

            body {
                color: snow;
            }

            h1 {
                margin-left: 10px;
            }

            label {
                display: inline-block;
                clear: left;
                width: 130px;
                margin: 10px;
            }

            input {
                width: 250px;
                padding: 5px;
            }

            #error-message {
                color: red;
                font-weight: bold;
            }

            .errors {
                margin-left: 20px;
            }

            .back {
                margin-left: auto;
                margin-right: 10px;
                height: 20px;
                padding: 10px;
                border: 2px solid black;
                background-color: grey;
                font-weight: bold;
            }

            .button {
                margin-left: 10px;
                margin-right: auto;
                width: 100px;
                border-radius: 5px;
                background-image: linear-gradient(to top, rgb(207, 207, 207) 16%, rgb(252, 252, 252) 79%); 
                padding: 5px 20px;
                border: 1px solid #000;
                color: black;
                text-decoration: none;
                display: flex;
                justify-content: center;
            }

            .form {
                margin-left: 10px;
                font-size: 20px;
            }

            .submit {
                margin: 10px;
                padding: 5px;
                width: 100px;
            }
        </style>
    </head>

    <body style="background-color:rgb(15 23 32);">
        <header>
            <h1>Add a Label</h1>
            <a href="{{ route('admin.labels') }}" class="back">Back</a>
        </header>
        <div class="errors">
        @if(count($errors) > 0)
            @foreach($errors as $error)
            <p id="error-message">{{ $error }}</p>
            @endforeach
        @endif
        </div>
        <div class="form">
            <form method="POST" action="{{ route('admin.store-label') }}">
                @csrf
                <label for="product_id">Product: </label>
                <select id="product_id" name="product_id" style="padding:4px;">
                @for ($i = 0; $i < count($products); $i++)
                    <option value="{{ $products[$i]['product_id'] }}">{{ $products[$i]['name'] }}</option>
                @endfor
                </select><br>
                <label for="valid_datetime">Valid From: </label>
                <input type="datetime-local" id="valid_datetime" name="valid_datetime" value="{{ date('Y-m-d H:i:s') }}"><br>
                <label for="pack_status">Pack Status: </label>
                <select id="pack_status" name="pack_status" style="padding:4px;">
                    <option value="0">0: printed</option>
                    <option value="1">1: onSale</option>
                    <option value="2">2: processed</option>
                </select><br>
                <input type="submit" value="Submit" class="submit">
            </form>
        </div>
    </body>
</html>