<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <title>Bento</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>
            header {
                display: flex;
                align-items:center;
            }

            body {
                color: snow;
            }

            table {
                border-collapse: separate;
                border-spacing: 30px 20px;
            }

            td {
                border: 2px solid black;
                padding: 10px;
                text-align: center;
                background-color: grey;
            }

            .product {
                width: 150px;
            }

            .product:hover {
                background-color: #0369a1;
                scale: 1.01;
                cursor: pointer;
            }

            .search {
                margin-left: auto;
                height: 20px;
            }

            .admin {
                margin-left: auto;
                margin-right: 10px;
                height: 20px;
                padding: 10px;
                border: 2px solid black;
                background-color: grey;
                font-weight: bold;
            }

        </style>
    </head>

    <body style="background-color:rgb(15 23 32);">
        <header>
            <img src="{{ Storage::url('store_logo.png') }}" alt="Logo" width="100" height="50" style="padding: 10px; border-radius: 24px;">
            <h1>Bento!</h1>
            <a href="{{ route('admin.products') }}" class="admin">Admin Panel</a>
        </header>
        @include('partials.full-list')
    </body>
</html>