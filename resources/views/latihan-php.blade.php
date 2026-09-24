<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latihan PHP</title>
</head>
<body>

    <h1>Latihan Dasar PHP</h1>

    <p>Nama: {{ $nama }}</p>

    <h3>Daftar Nilai</h3>

    <ul>
        @foreach ($nilai as $angka)
            <li>{{ $angka }}</li>
        @endforeach
    </ul>

    <p>Rata-rata: {{ number_format($rataRata, 2) }}</p>

    <p>Status: <strong>{{ $status }}</strong></p>

</body>
</html>