{{-- Sidebar --}}
<div id="sidebar" class="min-vh-100 p-3 text-white position-fixed" style="width: 250px; background-color: #3066BE; transition: 0.3s;">
    <ul class="nav flex-column">
        <li class="nav-item mt-5 mb-3">
            <a class="btn btn-sidebar w-100 fw-semibold" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        @if (Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('barang.tambah') }}">Tambah Barang</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('pesanan.tambah') }}">Tambah Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('barang.index') }}">Data Barang</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('pesanan.index') }}">Data Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('report.index') }}">Report Penyewaan</a>
            </li>
        @endif
        @if (Auth::user()->role === 'owner')
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('kategori.index') }}">Data Kategori</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('kategori.tambah') }}">Tambah Kategori</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-sidebar mb-3 w-100 fw-semibold" href="{{ route('register.form') }}">Buat Akun Baru</a>
            </li>
        @endif
    </ul>
</div>
