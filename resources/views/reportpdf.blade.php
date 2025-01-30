<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #3490dc; }
        table { border-collapse: collapse; width: 50%; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .image-container { text-align: left; margin-top: 20px; }
        .image-container img { max-width: 200px; border: 3px solid #3490dc; border-radius: 10px; }
    </style>
</head>
<body>
    <h1>[DETAIL INVOICE] Terima kasih atas pendaftaran Anda, {{ $data['nama_lengkap'] }}</h1>
    <p>Selamat bergabung dengan kami!</p>
    <p>Berikut adalah informasi pendaftaran Anda:</p>
    
    <table>
        <tr>
            <th>Informasi</th>
            <th>Detail</th>
        </tr>
        <tr>
            <td>ID Transaksi</td>
            <td>{{ $data['idTransaksi'] }}</td>
        </tr>
        <tr>
            <td>Nama Peserta</td>
            <td>{{ $data['nama_lengkap'] }}</td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td>{{ $data['nama_kategori'] }}</td>
        </tr>
        <tr>
            <td>Jumlah Slot</td>
            <td>{{ $data['qty_slot'] }}</td>
        </tr>
        <tr>
            <td>Bengkel atau Komunitas</td>
            <td>{{ $data['nama_team'] }}</td>
        </tr>
        <tr>
            <td>Nomor Handphone</td>
            <td>{{ $data['nomor_hp'] }}</td>
        </tr>
    </table>

    <div class="image-container">
        <p><strong>Foto Kendaraan</strong></p>
        <img src="{{ public_path('img/' . $data['foto']) }}" alt="Foto Kendaraan">
    </div>

    <br>
    
    <p>Silakan hubungi kami jika Anda memiliki pertanyaan lebih lanjut.</p>
    <p>Salam,</p>
    <p><strong>Customer Support</strong></p>
</body>
</html>
