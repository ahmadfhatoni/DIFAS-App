<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Report Penyewaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            padding: 20px;
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }

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
            width: 120px;
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
                    <h3 class="fw-bold" style="font-size: 20px;">LAPORAN PENYEWAAN</h3>
                    <div class="divider"></div>
                    <p class="mt-0 mb-0">
                        Jl. Suharso, Jungke RT 01 / RW 01 Karanganyar<br>
                        085103032721
                    </p>
                </td>
                <td style="width: 25%;" class="text-start">
                    <p class="fw-bold mb-1">Layanan:</p>
                    <span>- Tenda Pernikahan</span><br>
                    <span>- Kursi & Meja</span><br>
                    <span>- Alat Pesta</span><br>
                    <span>- Soundsystem</span>
                </td>
            </tr>
        </table>

        {{-- Tanggal Filter --}}
        @if ($tanggal_mulai || $tanggal_akhir)
            <p class="text-center fw-bold mt-3">
                Periode: 
                @if($tanggal_mulai) 
                    {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d M Y') }} 
                @else 
                    ... 
                @endif
                -
                @if($tanggal_akhir) 
                    {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }} 
                @else 
                    ... 
                @endif
            </p>
        @else
            <p class="text-center fw-bold mt-3">
                Menampilkan seluruh data
            </p>
        @endif

        {{-- Tabel --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>ID Pesanan</th>
                    <th>Nama Pemesan</th>
                    <th>Kode Barang</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($pesanan as $index => $p)
                    @php
                        $total = $p->getTotalDariDetailAttribute();
                        $rowCount = $p->details->count();
                        $totalRows = $rowCount + 2;
                        $grandTotal += $total;
                    @endphp

                    <tr>
                        <td rowspan="{{ $totalRows }}">{{ $index + 1 }}</td>
                        <td rowspan="{{ $totalRows }}">{{ \Carbon\Carbon::parse($p->tanggal_acara)->format('d-m-Y') }}</td>
                        <td rowspan="{{ $totalRows }}">{{ $p->id }}</td>
                        <td rowspan="{{ $totalRows }}">{{ $p->nama_pemesan }}</td>
                        <td colspan="4"></td>
                        <td rowspan="{{ $totalRows }}">Rp{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>

                    @foreach ($p->details as $d)
                        <tr>
                            <td>{{ $d->barang_id }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>Rp{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="4"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total keseluruhan --}}
        <table class="mt-3">
            <tr>
                <td class="text-start" colspan="8"><strong>Total Keseluruhan:</strong></td>
                <td class="fw-bold">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
