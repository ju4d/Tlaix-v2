@extends('layouts.app')
@section('title','Reports')
@section('content')
<h3>Inventory Levels</h3>
<canvas id="invChart" width="600" height="200"></canvas>

<h3>Expired Items</h3>
<ul>
    @foreach($expired as $e)
    <li>{{ $e->name }} — expired on {{ $e->expiration_date }}</li>
    @endforeach
</ul>

<script>
const labels = {!! json_encode($ingredients->pluck('name')) !!};
const data = {!! json_encode($ingredients->pluck('stock')) !!};
const ctx = document.getElementById('invChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: 'Stock', data: data }] },
});
</script>
@endsection
