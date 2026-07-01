<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPMB {{ $distribution->distribution_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
        }

        .container {
            padding: 20px 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.3;
        }

        .info-section table {
            width: 100%;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        table.main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table.main-table th.col-number {
            font-weight: normal;
            font-size: 9pt;
            background-color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .signature-section {
            margin-top: 25px;
        }
        .signature-section table {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SURAT PENGELUARAN BARANG<br>(SPMB)</h1>
        </div>

        <!-- Info -->
        <div class="info-section">
            <table>
                <tr>
                    <td>Kementrian/Lembaga : Kementrian Perhubungan</td>
                    <td>Kode : {{ $distribution->distribution_code }}</td>
                </tr>
                <tr>
                    <td>Unit Eselon I : Direktorat Jenderal Perhubungan Udara</td>
                    <td>Unit Peminta : {{ $distribution->recipient_name }}</td>
                </tr>
                <tr>
                    <td>Kantor/Satker : BLU UPBU Mutiara Sis Al-Jufri</td>
                    <td>Tgl. SPMB : {{ \Carbon\Carbon::parse($distribution->distribution_date)->locale('id')->isoFormat('D MMMM Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 8%;">Nomor Urut</th>
                    <th colspan="3">Banyaknya</th>
                    <th rowspan="2" style="width: 35%;">Nama Sub-sub Kelompok Barang</th>
                    <th colspan="2">Kode Sub-sub Kelompok Barang</th>
                </tr>
                <tr>
                    <th style="width: 8%;">Angka</th>
                    <th style="width: 14%;">Huruf</th>
                    <th style="width: 8%;">Satuan</th>
                    <th style="width: 13%;">Paraf Pengambil Barang</th>
                    <th style="width: 13%;">Petugas Gudang</th>
                </tr>
                <tr>
                    <th class="col-number">1</th>
                    <th class="col-number">2</th>
                    <th class="col-number">3</th>
                    <th class="col-number">4</th>
                    <th class="col-number">5</th>
                    <th class="col-number">6</th>
                    <th class="col-number">7</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distribution->distributionItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ number_format($item->qty, 0, ',', '.') }}</td>
                    <td>{{ ucwords(App\Helpers\NumberHelper::getNumberLetter($item->qty)) }}</td>
                    <td>{{ $item->item->itemStock->unit ?? 'Unit' }}</td>
                    <td>
                        {{ $item->item->name }}
                        @if($item->item->sku)
                            <br><small>SKU: {{ $item->item->sku }}</small>
                        @endif
                    </td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($distribution->note)
        <div style="margin-top: 15px;">
            <strong>Catatan:</strong>
            <p>{{ $distribution->note }}</p>
        </div>
        @endif

        <!-- Signatures -->
        <div class="signature-section">
            <table>
                <tr>
                    <td style="width: 33.3%">
                        <div class="text-center">Diterima :</div>
                        <div style="margin-top: 70px; margin-bottom: 70px"></div>
                        <div style="text-align: center;">
                            {{ $distribution->recipient_name }}
                        </div>
                    </td>
                    <td style="width: 33.3%">
                        <div class="text-center">
                            Dikeluarkan :
                            <br>
                            Koordinator BMN
                        </div>
                        <div style="margin-top: 60px; margin-bottom: 60px"></div>
                        <div style="text-align: center;">
                            {{ $koordinator_name }}
                        </div>
                    </td>
                    <td style="width: 33.3%">
                        <div class="text-center">
                            An. Kuasa Pengguna Barang :
                            <br>
                            Kasubag Keuangan dan Tata Usaha<br>
                            BLU UPBU Mutiara Sis Al-Jufri
                        </div>
                        <div style="margin-top: 50px; margin-bottom: 50px"></div>
                        <div style="text-align: center;">
                            {{ $kuasa_name }}
                        </div>
                    </td>
                </tr>
                {{-- <tr>
                    <td class="signature-box">
                        <div class="signature-title">Diterima :</div>
                        <div class="signature-subtitle">
                            Kasubbag/kasi BLU UPBU<br>
                            Mutiara Sis Al-Jufri
                        </div>
                        <div class="signature-name">{{ $distribution->recipient_name }}</div>
                    </td>
                    <td class="signature-box">
                        <div class="signature-title">
                            Dikeluarkan,<br>
                            Koordinator BMN
                        </div>
                        <div class="signature-subtitle">&nbsp;<br>&nbsp;</div>
                        <div class="signature-name">{{ $koordinator_name }}</div>
                    </td>
                    <td class="signature-box">
                        <div class="signature-title">
                            An. Kuasa Pengguna Barang<br>
                            Kasubag Keuangan Dan Tata Usaha<br>
                            BLU UPBU Mutiara Sis Al-Jufri
                        </div>
                        <div class="signature-subtitle">&nbsp;<br>&nbsp;</div>
                        <div class="signature-name">{{ $kuasa_name }}</div>
                    </td>
                </tr> --}}
            </table>
        </div>
    </div>
</body>
</html>