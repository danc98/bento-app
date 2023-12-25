<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Administration</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>
            header, div {
                display: flex;
                align-items: center;
            }

            body {
                color: snow;
            }

            h1 {
                margin-left: 10px;
            }

            table {
                margin: 0 auto;
                border-collapse: collapse;
                border-spacing: 40px 5px;
            }

            td, th {
                border: 2px solid black;
                background-color: grey;
                padding: 7px;
            }

            th {
                background-color: rgb(56,56,56);
            }

            p {
                padding: 0 10px 0 10px;
            }

            .navigator {
                margin-right: 10px;
                height: 20px;
                padding: 10px;
                border: 2px solid black;
                background-color: grey;
                font-weight: bold;
            }

            .button {
                margin-left: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
                background-image: linear-gradient(to top, rgb(207, 207, 207) 16%, rgb(252, 252, 252) 79%); 
                padding: 5px 20px;
                border: 1px solid #000;
                color: black;
                text-decoration: none;
                justify-content: center;
            }

            .check-row {
                border: none;
                background-color: transparent;
            }
        </style>
    </head>

    <body style="background-color:rgb(15 23 32);">
        <script type="text/javascript">
            function sendChecked() {
                var checkedBoxes = document.querySelectorAll('input[name=mycheckbox]:checked');
                var products = "";
                if (checkedBoxes.length !== 0) {
                    // Convert product ids to comma split string.
                    checkedBoxes.forEach((product) => {
                        products += product["value"] + ",";
                    })    
                } else {
                    alert("No items selected.");
                }

                document.getElementById("delete_checked").value = products;
            }
        </script>
        <header>
            <h1>Products</h1>
            <div style="margin-left: auto;">
                <a href="{{ route('admin.labels') }}" class="navigator">Labels</a>
                <a href="{{ route('product.index') }}" class="navigator">Back</a>
            </div>
        </header>
        <div>
            <a href="{{ route('admin.add-product') }}" class="button">Add a Product<a>
            <form method="POST" action="{{ route('admin.delete-product') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_products" id="delete_checked">
                <button type="submit" onClick="sendChecked();" class="button" style="padding: 6px 20px;">Remove Selected Products</button>
            </form>
        </div>
        <table>
            <tr>
                <th class="check-row"></th>
                <th>
                    <p>ID</p>
                </th>
                <th>
                    <p>Name</p>
                </th>
                <th>
                    <p>PLU Code</p>
                </th>
                <th>
                    <p>Price</p>
                </th>
                <th>
                    <p>Description</p>
                </th>
                <th>
                    <p>Image</p>
                </th>
                <th>
                    <p>Created At</p>
                </th>
                <th>
                    <p>Updated At</p>
                </th>
                <th>
                    <p>Current Stock</p>
                </th>
                <th>
                    <p>Category</p>
                </th>
            </tr>
            @for ($i = 0; $i < count($products); $i++)
            <tr>
                <td class="check-row">
                    <input type="checkbox" name="mycheckbox" id="{{ 'product' . $products[$i]['product_id'] }}" value="{{ $products[$i]['product_id'] }}">
                </td>
                <td>
                    <p>{{ $products[$i]['product_id'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['name'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['plu_cd'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['price'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['desc'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['img'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['ins_datetime'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['upd_datetime'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['stock'] }}</p>
                </td>
                <td>
                    <p>{{ $products[$i]['category'] }}</p>
                </td>
            </tr>
            @endfor
        </table>
    </body>
</html>