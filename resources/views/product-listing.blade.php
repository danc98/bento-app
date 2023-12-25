<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bento Listing</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>
            body {
                color: snow;
            }

            table {
                margin: 0 auto;
                border-collapse: separate;
                border-spacing: 40px 5px;
            }

            p {
                font-size: 18px;
                word-wrap: break-word;
            }

            .product {
                width: 400px;
                border: 2px solid black;
                text-align: center;
                padding: 5px 50px 100px;
                background-color: grey;
            }

            .button {
                border-radius: 5px;
                background-image: linear-gradient(to top, rgb(207, 207, 207) 16%, rgb(252, 252, 252) 79%);
                padding: 5px 30px;
                border: 1px solid #000;
                color: black;
                text-decoration: none;
            }
        </style>
    </head>

    <body style="background-color:rgb(15 23 32);">
        <div>
            <a class="button" href="{{ $return_url }}">Back</a>
            <table>
                <tr>
                @if(isset($product))
                    <td class="product">
                        <h2>{{ $product['name'] }}</h2>
                        <img src="{{ Storage::url($product['img']) }}" alt="Product Image" width=220 height=200>
                        <p>In stock: {{ $product['stock'] }}</p>
                        <p>Price: {{ $product['price'] }}</p>
                        <p>{{ $product['desc'] }}</p>
                    </td>
                @else
                    <p>This product just went out of stock! Sorry!</p>
                @endif
                </tr>
            </table>
        </div>
    </body>
</html>