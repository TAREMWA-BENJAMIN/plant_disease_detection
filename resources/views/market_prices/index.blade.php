@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Crop Market Prices</h2>
    <div class="table-responsive">
		<div class="card" style="width: 100%;">
			API - on Information Agriculture Marketing from  <a href="https://farmgainafrica.org">Farmgain Africa</a>
    		<img src="https://farmgainafrica.org/wp-content/themes/farm/images/farmgainafrica.png" class="card-img-top" alt="Farmgain Africa">
		</div>
		<br>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>COMMODITY</th>
                    <th>UNIT</th>
                    <th>RETAIL PRICE</th>
                    <th>WHOLESALE PRICE</th>
                    <th>DIFFERENCE</th>
                    <th>CHANGE (%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marketPrices as $price)
                    <tr>
                        <td>{{ $price->commodity }}</td>
                        <td>{{ $price->unit }}</td>
                        <td>{{ $price->retail_price }}</td>
                        <td>{{ $price->wholesale_price }}</td>
                        <td>{{ $price->difference }}</td>
                        <td>{{ $price->change_percentage }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No market price data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection