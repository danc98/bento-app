<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Add a Product</title>

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
            <h1>Add a Product</h1>
            <a href="{{ route('admin.products') }}" class="back">Back</a>
        </header>
        <div class="errors">
        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
            <p id="error-message">{{ $error }}</p>
            @endforeach
        @endif
        </div>
        <div class="form">
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.store-product') }}">
                @csrf
                <label for="item_name">Name: </label>
                <input type="text" id="item_name" name="item_name"><br>
                <label for="plu_cd">PLU Code: </label>
                <input type="text" id="plu_cd" name="plu_cd"><br>
                <label for="item_price">Price: </label>
                <input type="text" id="item_price" name="item_price"><br>
                <label for="item_desc">Description: </label>
                <input type="text" id="item_desc" name="item_desc"><br>
                <label for="item_img">Image: </label>
                <input type="file" id="item_img" name="item_img" accept=".png,.jpeg,.jpg"><br>
                <label for="category">Category: </label>
                <input type="text" id="category" name="category"><br>
                <input type="submit" value="Submit" class="submit">
            </form>
        </div>
    </body>
</html>