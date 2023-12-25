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
                var labels = "";
                if (checkedBoxes.length !== 0) {
                    // Convert label ids to comma split string.
                    checkedBoxes.forEach((label) => {
                        labels += label["value"] + ",";
                    })    
                } else {
                    alert("No items selected.");
                }
                document.getElementById("delete_checked").value = labels;
            }
        </script>
        <header>
            <h1>Labels</h1>
            <a href="{{ route('admin.products') }}" class="back">Back</a>
        </header>
        <div>
            <a href="{{ route('admin.add-label') }}" class="button">Add a Label<a>
            <form method="POST" action="{{ route('admin.delete-label') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_labels" id="delete_checked">
                <button type="submit" onClick="sendChecked();" class="button" style="padding: 6px 20px;">Remove Selected Labels</button>
            </form>
        </div>
        <table>
            <tr>
                <th class="check-row"></th>
                <th>
                    <p>ID</p>
                </th>
                <th>
                    <p>Product</p>
                </th>
                <th>
                    <p>Created At</p>
                </th>
                <th>
                    <p>Valid From</p>
                </th>
                <th>
                    <p>Updated At</p>
                </th>
                <th>
                    <p>Pack Status</p>
                </th>
            </tr>
            @for ($i = 0; $i < count($labels); $i++)
            <tr>
                <td class="check-row">
                    <input type="checkbox" name="mycheckbox" id="{{ 'label' . $labels[$i]['label_id'] }}" value="{{ $labels[$i]['label_id'] }}">
                </td>
                <td>
                    <p>{{ $labels[$i]['label_id'] }}</p>
                </td>
                <td>
                    <p>{{ $labels[$i]['product'] }}</p>
                </td>
                <td>
                    <p>{{ $labels[$i]['prod_datetime'] }}</p>
                </td>
                <td>
                    <p>{{ $labels[$i]['valid_datetime'] }}</p>
                </td>
                <td>
                    <p>{{ $labels[$i]['update_datetime'] }}</p>
                </td>
                <td>
                    <p>{{ $labels[$i]['pack_status'] }}</p>
                </td>
            </tr>
            @endfor
        </table>
    </body>
</html>