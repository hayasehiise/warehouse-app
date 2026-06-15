<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Stok & Distribusi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .period {
            margin-top: 8px;
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        td.number {
            text-align: right;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Stok & Distribusi Item</h1>
        <div class="period">Periode: {{ $startDate }} s/d {{ $endDate }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Item</th>
                <th width="20%">Kategori</th>
                <th width="15%" class="number">Total Stock</th>
                <th width="15%" class="number">Stock Keluar</th>
                <th width="15%" class="number">Sisa Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['item_name'] }}</td>
                    <td>{{ $row['category_name'] }}</td>
                    <td class="number">{{ number_format($row['stock_total'], 0, ',', '.') }}</td>
                    <td class="number">{{ number_format($row['stock_distributed'], 0, ',', '.') }}</td>
                    <td class="number"><strong>{{ number_format($row['available_stock'], 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 20px; color: #888;">
                        Tidak ada data item untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan digenerate otomatis pada: {{ $printedAt }}
    </div>
</body>

</html>
