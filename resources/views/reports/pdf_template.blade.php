<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Aset PT Telkomsel</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #ef4444; /* Telkomsel Red */
            padding-bottom: 15px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .subtitle {
            font-size: 10px;
            color: #4b5563;
            margin-top: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-info {
            float: right;
            text-align: right;
            font-size: 9px;
            color: #4b5563;
            line-height: 1.3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            color: #1f2937;
            text-align: left;
            font-weight: bold;
            padding: 8px;
            font-size: 9px;
            border-bottom: 1px solid #d1d5db;
            text-transform: uppercase;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-yellow {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="meta-info">
            Tanggal Cetak: {{ $date }}<br>
            Dicetak Oleh: Sistem Inventaris
        </div>
        <h1 class="title">PT TELKOMSEL INDONESIA</h1>
        <div class="subtitle">LAPORAN DATA ASET DAN INVENTARIS KANTOR</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode Barang</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%;">Stok</th>
                <th style="width: 20%;">Lokasi</th>
                <th style="width: 10%;">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td style="font-weight: bold; color: #1f2937;">{{ $product->code }}</td>
                    <td style="font-weight: 500;">{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td style="font-weight: bold;">{{ $product->stock }}</td>
                    <td>{{ $product->storage_location }}</td>
                    <td>
                        @if($product->condition == 'Baik')
                            <span class="badge badge-green">Baik</span>
                        @elseif($product->condition == 'Rusak Ringan')
                            <span class="badge badge-yellow">Rusak Ringan</span>
                        @else
                            <span class="badge badge-red">Rusak Berat</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Manajemen Inventaris PT Telkomsel &copy; {{ date('Y') }}.
    </div>
</body>
</html>
