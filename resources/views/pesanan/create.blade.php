@extends('layouts.app')

@section('title', 'Form Tambah Pesanan')

@section('content')
<div class="main-wrapper justify-content-center">
    <div class="bg-primary card p-4 position-relative" style="border-radius: 15px; width: 100%;">

        <div class="text-center mb-4">
            <h4 class="text-white fw-bold m-0">FORM TAMBAH PESANAN</h4>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data tidak bisa disimpan!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Container -->
        <div class="row justify-content-center">
            {{-- PILIH BARANG --}}
            <div class="col-md-4 mb-3">
                <div class="card p-3 bg-secondary">
                    <h5 class="text-center fw-bold">PILIH BARANG</h5>
                    <div class="mb-2">
                        <label>Nama Barang</label>
                        <select id="barang" class="form-select">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barangs as $barang)
                                <option 
                                    value="{{ $barang->id }}" 
                                    data-harga="{{ $barang->harga_sewa }}" 
                                    data-stok="{{ $barang->stok }}" 
                                    data-nama="{{ $barang->nama }}">
                                    {{ $barang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Harga Sewa Rp.</label>
                        <input type="text" id="harga" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Stok</label>
                        <input type="text" id="stok" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="number" id="jumlah" class="form-control" min="1">
                    </div>
                    <button class="btn btn-primary fw-semibold" onclick="tambahBarang()">Tambah</button>
                </div>
            </div>

            {{-- DETAIL PESANAN --}}
            <div class="col-md-8 mb-3">
                <div class="card p-3 bg-secondary">
                    <h5 class="text-center fw-bold">DETAIL PESANAN</h5>                        
                    <form method="POST" action="{{ route('pesanan.store') }}">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Nama Pemesan</label>
                                <input type="text" name="nama_pemesan" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>No Telepon</label>                                    
                                <input type="text" name="no_telepon" class="form-control" required minlength="10">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" required></textarea>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label>Tanggal Acara</label>
                                <input type="date" name="tanggal_acara" class="form-control" min="{{ date('Y-m-d') }}" required>                                
                            </div>
                        </div>
                        
                        <input type="hidden" name="detail_pesanan" id="detail_pesanan">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center align-middle bg-white mt-3">
                                <thead class="table-primary" style="position: sticky; top: 0;">
                                    <tr>
                                        <th>ID Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>                                        
                                        <th>Harga Sewa</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-detail">
                                    <!-- Data akan diisi melalui JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <div class="fw-semibold">Jumlah Item: <span id="total-jumlah" class="text-theme-blue">0</span></div>
                            <div class="fw-semibold">Total Harga: <span id="total-harga" class="text-theme-blue">Rp. 0</span></div>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary fw-semibold me-2">Simpan</button>
                            <button type="reset" class="btn btn-secondary fw-semibold">Batalkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let detailList = [];
    let sisaStok = {};

    document.getElementById('barang').addEventListener('change', function () {
        const selected = this.selectedOptions[0];
        const harga = parseInt(selected.dataset.harga || 0);
        const stok = parseInt(selected.dataset.stok || 0);
        const id = this.value;

        if (!(id in sisaStok)) {
            sisaStok[id] = stok;
        }

        document.getElementById('harga').value = harga;
        document.getElementById('stok').value = sisaStok[id];
    });


    function tambahBarang() {
        const barangSelect = document.getElementById('barang');
        const id = barangSelect.value;
        if (!id) return alert('Pilih barang terlebih dahulu.');

        const nama = barangSelect.selectedOptions[0].dataset.nama;
        const harga = parseInt(barangSelect.selectedOptions[0].dataset.harga || 0);
        const jumlahInput = document.getElementById('jumlah');
        const jumlah = parseInt(jumlahInput.value || 0);

        if (isNaN(jumlah) || jumlah < 1) return alert('Jumlah harus lebih dari 0.');

        if (typeof sisaStok[id] === 'undefined') {
            const stokAsli = parseInt(barangSelect.selectedOptions[0].dataset.stok || 0);
            sisaStok[id] = stokAsli;
        }

        if (jumlah > sisaStok[id]) {
            return alert(`Stok tidak mencukupi. Maksimal ${sisaStok[id]} lagi.`);
        }

        const existing = detailList.find(item => item.id === id);
        if (existing) {
            existing.jumlah += jumlah;
            existing.subtotal = existing.jumlah * harga;
        } else {
            detailList.push({ id, nama, jumlah, harga, subtotal: harga * jumlah });
        }

        sisaStok[id] -= jumlah;
        document.getElementById('stok').value = sisaStok[id];
        updateTabel();
        jumlahInput.value = '';
    }

    function hapusBarang(index) {
        const barang = detailList[index];
        sisaStok[barang.id] += barang.jumlah;

        // jika barang yang dihapus sedang dipilih, update stoknya di input
        if (document.getElementById('barang').value === barang.id) {
            document.getElementById('stok').value = sisaStok[barang.id];
        }

        detailList.splice(index, 1);
        updateTabel();
    }

    function updateTabel() {
        const tbody = document.getElementById('tabel-detail');
        tbody.innerHTML = '';
        let total = 0;
        let totalJumlah = 0;

        detailList.forEach((item, index) => {
            total += item.subtotal;
            totalJumlah += item.jumlah;

            tbody.innerHTML += `
                <tr>
                    <td><input type="hidden" name="id_barang[]" value="${item.id}">${item.id}</td>
                    <td>${item.nama}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp. ${item.harga.toLocaleString('id-ID')}</td>
                    <td>Rp. ${item.subtotal.toLocaleString('id-ID')}</td>
                    <td><button type="button" class="btn btn-danger btn-sm fw-semibold" onclick="hapusBarang(${index})">Hapus</button></td>
                </tr>
            `;
        });

        document.getElementById('total-harga').innerText = `Rp. ${total.toLocaleString('id-ID')}`;
        document.getElementById('total-jumlah').innerText = detailList.length;
        document.getElementById('detail_pesanan').value = JSON.stringify(detailList);
    }
</script>
@endsection
