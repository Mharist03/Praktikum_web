<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Form Mahasiswa</title>
</head>
<body>

    <h1>Hasil Data Mahasiswa</h1>

    <p>
        <strong>Nama:</strong>
        {{ $data['nama'] }}
    </p>

    <p>
        <strong>Email:</strong>
        {{ $data['email'] }}
    </p>

    <p>
        <strong>Usia:</strong>
        {{ $data['usia'] }}
    </p>

    <p>
        <strong>NIM:</strong>
        {{ $data['nim'] }}
    </p>

    <p>
        Data berhasil dikirim dan divalidasi.
    </p>

    <a href="{{ route('form.mahasiswa') }}">Kembali ke Form</a>

</body>
</html>