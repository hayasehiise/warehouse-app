@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Berita Acara Stock Opname</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
            margin: 15px;
        }

        /* Header Institution */
        .header-institution {
            text-align: center;
        }

        .header-institution h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .header-institution h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }

        .header-institution h3 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }

        /* Header Address */
        .header-address {
            font-size: 9pt;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        .header-address table {
            width: 100%;
            border: none;
        }

        .header-address td {
            border: none;
            padding: 1px 5px;
            vertical-align: top;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            margin-bottom: 12px;
        }

        .report-title h3 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .report-title .period {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Description */
        .report-description {
            font-size: 10pt;
            text-align: justify;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        /* Main Table */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 9pt;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: middle;
        }

        table.main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
        }

        table.main-table td.number {
            text-align: right;
        }

        table.main-table td.center {
            text-align: center;
        }

        /* Table header repetition */
        table.main-table thead {
            display: table-header-group;
        }

        table.main-table tr {
            page-break-inside: avoid;
        }

        /* Category header */
        .category-header {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            padding: 5px;
            vertical-align: top;
            text-align: center;
            width: 50%;
        }

        .signature-box {
            width: 100%;
            max-width: 250px;
            margin: 0 auto;
        }

        .signature-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .signature-space {
            margin-bottom: 80px;
        }

        .signature-name {
            font-size: 10pt;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 3px;
            display: inline-block;
            min-width: 180px;
        }

        .signature-nip {
            font-size: 9pt;
        }

        /* Footer */
        .footer-info {
            margin-top: 15px;
            font-size: 9pt;
            text-align: right;
            font-style: italic;
        }
    </style>
</head>

<body>
    {{-- Header Institution --}}
    <div class="header-institution">
        <h1>KEMENTERIAN PERHUBUNGAN</h1>
        <h2>DIREKTORAT JENDERAL PERHUBUNGAN UDARA</h2>
        <h3>BADAN LAYANAN UMUM<br>KANTOR UPBU MUTIARA SIS AL - JUFRI</h3>
    </div>

    {{-- Address --}}
    <div class="header-address">
        <table>
            <tr>
                <td style="width: 40%;">JL. ABD. RAHMAN SALEH<br>
                    PALU, Kode Pos : 94121<br>
                    GEDUNG ADMINISTRASI</td>
                <td style="width: 25%; text-align: center;">
                    TELP : 0451 481702<br>
                    FAX : 0451 481087<br>
                    SMS Center : 0853 4254 1620
                </td>
                <td style="width: 35%; text-align: right;">
                    E-mail : bandara_mutiara08@yahoo.co.id<br>
                    Website : bandaramutiarasaj.com
                </td>
            </tr>
        </table>
    </div>

    {{-- Report Title --}}
    <div class="report-title">
        <h3>BERITA ACARA STOCK OPNAME PERSEDIAAN</h3>
        <div class="period">PER {{ strtoupper(Carbon::parse($endDate)->locale('id')->isoFormat('MMMM Y')) }}</div>
    </div>

    {{-- Description --}}
    <div class="report-description">
        Pada hari ini {{ Carbon::now()->locale('id')->isoFormat('dddd') }} tanggal
        {{ Carbon::now()->format('d') }} bulan
        {{ Carbon::now()->locale('id')->isoFormat('MMMM') }} tahun
        {{ Carbon::now()->format('Y') }} telah dilaksanakan Stock Opname Persediaan
        pada Kantor BLU UPBU Mutiara Sis Al-Jufri Palu. Dengan hasil sebagai berikut :
    </div>

    {{-- Main Table --}}
    <table class="main-table">
        <thead>
            <tr>
                <th width="4%" rowspan="2">NO</th>
                <th width="42%" rowspan="2">JENIS BARANG</th>
                <th width="18%" colspan="2">TOTAL STOCK<br>(JUMLAH)</th>
                <th width="18%" colspan="2">TERDISTRIBUSI<br>(JUMLAH)</th>
                <th width="9%" rowspan="2">SISA STOCK<br>(JUMLAH)</th>
                <th width="9%" rowspan="2">KET</th>
            </tr>
            <tr>
                <th>JUMLAH</th>
                <th>SATUAN</th>
                <th>JUMLAH</th>
                <th>SATUAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentCategory = null;
                $rowNumber = 1;
            @endphp

            @forelse($reportData as $row)
                {{-- Category Header --}}
                @if ($row['category_name'] !== $currentCategory)
                    @php $currentCategory = $row['category_name']; @endphp
                    <tr class="category-header">
                        <td colspan="8" style="text-align: left; padding-left: 8px;">
                            {{ strtoupper($currentCategory) }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <td class="center">{{ $rowNumber++ }}</td>
                    <td>{{ $row['item_name'] }}</td>
                    <td class="number">{{ number_format($row['stock_total'], 0, ',', '.') }}</td>
                    <td class="center">{{ $row['item_unit'] }}</td>
                    <td class="number">{{ number_format($row['stock_distributed'], 0, ',', '.') }}</td>
                    <td class="center">{{ $row['item_unit'] }}</td>
                    <td class="number">
                        {{ number_format($row['available_stock'], 0, ',', '.') }}
                    </td>
                    <td class="center">-</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 20px;">
                        Tidak ada data item untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Signature Section --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-box">
                        <div class="signature-title">Mengetahui,</div>
                        <div class="signature-space"></div>
                        <div class="signature-name" style="margin-top: 20px">
                            {{ $approver ?? '.........................' }}</div>
                        <div class="signature-nip">NIP. {{ $approver_nip ?? '.........................' }}</div>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        <div class="signature-title">Palu, {{ Carbon::now()->locale('id')->isoFormat('DD MMMM Y') }}
                        </div>
                        <div class="signature-title">Dibuat Oleh,</div>
                        <div class="signature-space"></div>
                        <div class="signature-name">{{ $creator ?? '.........................' }}</div>
                        <div class="signature-nip">NIP. {{ $creator_nip ?? '.........................' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer Info --}}
    <div class="footer-info">
        Dicetak pada: {{ Carbon::now()->format('d F Y, H:i') }} WIB
    </div>
</body>

</html>
