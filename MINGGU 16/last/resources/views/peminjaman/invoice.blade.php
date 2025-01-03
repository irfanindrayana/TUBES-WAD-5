<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section p {
            margin: 5px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            margin-right: 50px;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 100%;
        }
        .signature-name {
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" alt="Logo Godown">
        <h1>INVOICE PEMINJAMAN</h1>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>Informasi Peminjam:</h3>
            <p>Nama: {{ $peminjaman->nama_peminjam }}</p>
            <p>ID: {{ $peminjaman->id }}</p>
            <p>Tanggal Pinjam: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y H:i:s') }}</p>
        </div>
        <div class="info-box">
            <h3>Status Peminjaman:</h3>
            <p>Status: {{ $peminjaman->status }}</p>
            <p>Tanggal Kembali: {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y H:i:s') : '-' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $peminjaman->nama_barang }}</td>
                <td>{{ $peminjaman->jumlah_barang }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Peminjam</p>
            <div class="signature-line"></div>
            <p class="signature-name">{{ $peminjaman->nama_peminjam }}</p>
        </div>
    </div>
</body>
</html> 