<section>
    <script type="text/javascript">
        $(function() {
            $('#category-select').on('change', function(event) {
                var url = "{{ route('product.filter', ':id') }}";
                if (this.value) {
                    url = url.replace(':id', this.value);
                } else {
                    url = "{{ route('product.index') }}";
                }
                window.location.href = url;
            });
        });
    </script>
    <div>
        <div style="display: flex; align-items: center;">
            <h2 style="margin-left: 10px; margin-right: 10px;">Currently available:</h2>
            <form method="get" action="{{ route('product.index') }}">
                <button type="submit" style="height: 30px; padding: 5px 30px;">Update</button>
            </form>
            @if(isset($categories))
                <div class="search">
                    <form method="get" action="{{ route('product.search') }}">
                        <input type="text" placeholder="Search by product..." name="q">
                        <input type="submit" value="Search">
                    </form>
                <select name="categories" id="category-select" class="search" style="margin-top:10px">
                    <option value="">Filter by category...</option>
                @foreach ($categories as $category)
                    <option value="{{ $category['category_name'] }}">
                    {{ $category['category_name'] }}
                    </option>
                @endforeach
                </select>
                </div>
            @endif
        </div>
        <table>
            @if(isset($products))
                @if(count($products) == 0)
                <p style="margin-left: 10px;">Nothing is in stock right now. Sorry!</p>
                @else
                <tr>
                    @for ($i = 0; $i < count($products); $i++)
                        @if ($i % 8 == 0 && $i != 0)
                        </tr><tr>
                        @endif
                        <td class="product" onclick="window.location='{{ route('product.show', ['id' => $products[$i]['product_id']]) }}'">
                            <img src="{{ Storage::url($products[$i]['img']) }}" alt="Product Image" width="125" height="100" style="padding: 20px 10px 20px;">
                            <p>{{ $products[$i]['name'] }}</p>
                            <p>In stock: {{ $products[$i]['stock'] }}</p>
                        </td>
                    @endfor
                </tr>
                @endif
            @else
                <p style="margin-left: 10px;">Press the update button to check the current stock!</p>
            @endif
        </table>
    </div>
</section>