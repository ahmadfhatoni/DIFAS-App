<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-0 { margin-top: 0; }
        .mt-3 { margin-top: 12px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        .no-border {
            border: none;
        }

        .no-border td {
            border: none;
        }

        .logo {
            width: 150px;
        }

        .divider {
            height: 2px;
            background-color: #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <table class="no-border">
            <tr>
                <td style="width: 25%;" class="text-end">
                    <img src="{{ public_path('logo/lOGO.png') }}" alt="Logo" class="logo">
                </td>
                <td style="width: 50%;" class="text-center">
                    <h3 class="fw-bold" style="font-size: 24px;">INVOICE PEMBAYARAN</h3>
                    <div class="divider"></div>
                    <p class="mt-0 mb-0">
                        Jl. Suharso, Jungke RT 01/ RW 01 Karanganyar (Lor Kelurahan Jungke)<br>
                        085103032721
                    </p>
                </td>
                <td style="width: 25%;" class="text-start">
                    <p class="fw-bold mb-1">Menyediakan:</p>
                    <span>- Tenda Pernikahan</span><br>
                    <span>- Kursi</span><br>
                    <span>- Alat Pesta</span><br>
                    <span>- Soundsystem</span>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        {{-- Informasi Pesanan --}}
        <table class="no-border">
            <tr>
                <td style="text-align: left;"><strong>Nomor Pesanan:</strong> {{ $pesanan->id }}</td>
                <td colspan="2" style= "text-align: left;">
                    <strong>Data Penyewa:</strong><br>
                    Nama: {{ $pesanan->nama_pemesan }}<br>
                    No Telepon: {{ $pesanan->no_telepon }}<br>
                    Alamat: {{ $pesanan->alamat }}
                </td>
                <td style="text-align: right;"><strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d/m/Y') }}</td>
            </tr>
            
        </table>

        {{-- Tabel Detail --}}
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Sewa</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($pesanan->details as $index => $item)
                    @php $total += $item->subtotal; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->barang_id }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table>
            <tr>
                <td class="text-start"><strong>Jumlah Item Barang:</strong> {{ count($pesanan->details) }}</td>
                <td class="text-end"><strong>Total:</strong> Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <p class="mt-3"><strong>Perhatian:</strong><br>
        Barang rusak, pecah, hilang, tanggungan penyewa <strong>(mengganti)</strong></p>

    </div>
</body>
</html>
