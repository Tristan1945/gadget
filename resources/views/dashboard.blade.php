@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('content')
<div class="gadget-hero p-4 p-md-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <p class="text-white-50 mb-2">Ringkasan operasional hari ini</p>
            <h2 class="font-weight-bold text-white mb-2">Halo, {{ auth()->user()?->name }}</h2>
            <p class="mb-0 text-white-50">Pantau stok, kategori, dan nilai persediaan dalam satu tampilan.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-light mt-3 mt-md-0">
            <i class="fas fa-search mr-2"></i>Kelola Produk
        </a>
    </div>
</div>

<div class="row mb-4">
    @php
        $stats = [
            ['label' => 'Total Produk', 'value' => number_format($totalProducts), 'icon' => 'fa-mobile-alt', 'color' => '#7667f7'],
            ['label' => 'Kategori', 'value' => number_format($totalCategories), 'icon' => 'fa-tags', 'color' => '#22d3ee'],
            ['label' => 'Total Unit', 'value' => number_format($totalStock), 'icon' => 'fa-boxes', 'color' => '#10b981'],
            ['label' => 'Stok Menipis', 'value' => number_format($lowStockProducts), 'icon' => 'fa-triangle-exclamation', 'color' => '#fb7185'],
        ];
    @endphp
    @foreach ($stats as $stat)
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card gadget-stat h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">{{ $stat['label'] }}</p>
                        <h3 class="font-weight-bold mb-0">{{ $stat['value'] }}</h3>
                    </div>
                    <div class="stat-icon" style="color: {{ $stat['color'] }}">
                        <i class="fas {{ $stat['icon'] }} fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row mb-4">
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="font-weight-bold mb-1">Produk per Kategori</h5>
                        <p class="text-muted small mb-0">Distribusi jumlah produk</p>
                    </div>
                </div>
                <div style="height:300px"><canvas id="categoryChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="font-weight-bold mb-1">Kondisi Stok</h5>
                <p class="text-muted small">Komposisi tingkat ketersediaan</p>
                <div style="height:240px"><canvas id="stockChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="font-weight-bold mb-1">Stok per Kategori</h5>
                        <p class="text-muted small mb-0">Total unit yang tersedia</p>
                    </div>
                </div>
                <div style="height:260px"><canvas id="stockByCategoryChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100 gadget-stat">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <p class="text-muted mb-2">Estimasi Nilai Persediaan</p>
                    <h3 class="font-weight-bold" style="color:#a99fff">
                        Rp {{ number_format($inventoryValue, 0, ',', '.') }}
                    </h3>
                </div>
                <p class="small text-muted mb-0">Nilai dihitung dari harga produk dikalikan jumlah stok saat ini.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-7 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold mb-0">Produk Terbaru</h5>
                <a href="{{ route('products.index') }}" class="small">Lihat semua</a>
            </div>
            <div class="gadget-table-wrap border-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr></thead>
                        <tbody>
                        @forelse ($latestProducts as $product)
                            <tr>
                                <td>
                                    <a class="font-weight-bold" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                    <div class="small text-muted">{{ $product->product_code }}</div>
                                </td>
                                <td>{{ $product->category?->name }}</td>
                                <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                <td><span class="badge {{ $product->stock <= 5 ? 'badge-danger' : ($product->stock <= 20 ? 'badge-warning' : 'badge-success') }}">{{ $product->stock }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold mb-0">Stok Perlu Perhatian</h5>
                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="small">Lihat</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse ($lowStockItems as $product)
                    <a href="{{ route('products.show', $product) }}" class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <div class="font-weight-bold">{{ $product->name }}</div>
                            <div class="small text-muted">{{ $product->product_code }} · {{ $product->category?->name }}</div>
                        </div>
                        <span class="badge badge-danger badge-pill">{{ $product->stock }} unit</span>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">Stok aman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#98a5bd';
Chart.defaults.borderColor = 'rgba(148,163,184,.12)';
const categoryLabels = @json($categoryStats->pluck('name')->values());
const productCounts = @json($categoryStats->pluck('product_count')->map(fn ($v) => (int) $v)->values());
const stockTotals = @json($categoryStats->pluck('stock_total')->map(fn ($v) => (int) $v)->values());
const gradientBar = (context) => {
    const chart = context.chart;
    const area = chart.chartArea;
    if (!area) return '#7667f7';
    const gradient = chart.ctx.createLinearGradient(0, area.bottom, 0, area.top);
    gradient.addColorStop(0, '#5b53cc');
    gradient.addColorStop(1, '#22d3ee');
    return gradient;
};
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: { labels: categoryLabels, datasets: [{ label: 'Jumlah produk', data: productCounts, backgroundColor: gradientBar, borderRadius: 8, borderSkipped: false }] },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true, ticks:{precision:0}}} }
});
new Chart(document.getElementById('stockChart'), {
    type:'doughnut',
    data:{ labels:['Menipis','Cukup','Banyak'], datasets:[{data:@json($stockStatus), backgroundColor:['#fb7185','#fbbf24','#34d399'], borderWidth:0, hoverOffset:6}] },
    options:{responsive:true, maintainAspectRatio:false, cutout:'68%', plugins:{legend:{position:'bottom', labels:{usePointStyle:true,padding:14}}}}
});
new Chart(document.getElementById('stockByCategoryChart'), {
    type:'bar',
    data:{labels:categoryLabels,datasets:[{label:'Unit stok',data:stockTotals,backgroundColor:gradientBar,borderRadius:8,borderSkipped:false}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}
});
</script>
@endpush
