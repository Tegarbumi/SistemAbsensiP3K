<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Absensi PPPK</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h3 {
            margin: 3px 0 0 0;
            font-size: 13px;
            font-weight: normal;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .meta-table td {
            padding: 2px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #777;
            padding: 5px 4px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 25px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 250px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pemerintah Kabupaten Subang</h2>
        <h3>Dinas Kearsipan dan Perpustakaan</h3>
        <p style="margin: 3px 0 0 0; font-size: 10px; color: #555;">Jl. Mayjen Sutoyo No. 1, Subang, Jawa Barat</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>Perihal</strong></td>
            <td style="width: 35%;">: Rekapitulasi Presensi Pegawai PPPK</td>
            <td style="width: 20%;"><strong>Unit Kerja</strong></td>
            <td style="width: 30%;">: {{ $unitName }}</td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>: {{ $monthName }} {{ $year }}</td>
            <td><strong>Hari Kerja Efektif</strong></td>
            <td>: {{ $workingDays }} Hari</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 18%;">Nama Pegawai</th>
                <th style="width: 13%;">NIP / No PPPK</th>
                <th style="width: 15%;">Unit Kerja / Jabatan</th>
                <th style="width: 6%;">Hadir</th>
                <th style="width: 6%;">T'lambat</th>
                <th style="width: 6%;">Izin</th>
                <th style="width: 6%;">Sakit</th>
                <th style="width: 6%;">Cuti</th>
                <th style="width: 6%;">Dinas</th>
                <th style="width: 6%;">Alpha</th>
                <th style="width: 8%;">Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recap as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $row['nama'] }}</strong></td>
                    <td>{{ $row['nip'] }}</td>
                    <td>
                        {{ $row['unit'] }}<br>
                        <span style="color: #666; font-size: 9px;">{{ $row['jabatan'] }}</span>
                    </td>
                    <td class="text-center">{{ $row['hadir'] }}</td>
                    <td class="text-center">{{ $row['terlambat'] }}</td>
                    <td class="text-center">{{ $row['izin'] }}</td>
                    <td class="text-center">{{ $row['sakit'] }}</td>
                    <td class="text-center">{{ $row['cuti'] }}</td>
                    <td class="text-center">{{ $row['dinas'] }}</td>
                    <td class="text-center">{{ $row['alpha'] }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $row['rate'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data pegawai yang terdaftar pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Subang, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 50px;">Kepala Dinas Kearsipan dan Perpustakaan<br>Kabupaten Subang</p>
            <p style="font-weight: bold; text-decoration: underline;">( .................................................. )</p>
            <p style="font-size: 10px;">NIP. ..................................................</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
