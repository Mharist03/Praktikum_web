<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>
</head>
<body>

    <h2>Edit Data Mahasiswa</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('gagal'))
        <p style="color: red;">{{ session('gagal') }}</p>
    @endif

    <form action="{{ route('mahasiswa.update', $mahasiswa['id']) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div>
            <label>NIM</label><br>
            <input
                type="text"
                name="nim"
                value="{{ old('nim', $mahasiswa['nim']) }}"
                maxlength="12"
                required
            >
        </div>

        <br>

        <div>
            <label>Nama</label><br>
            <input
                type="text"
                name="nama"
                value="{{ old('nama', $mahasiswa['nama']) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Email</label><br>
            <input
                type="email"
                name="email"
                value="{{ old('email', $mahasiswa['email']) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Usia</label><br>
            <input
                type="number"
                name="usia"
                value="{{ old('usia', $mahasiswa['usia']) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Program Studi</label><br>
            <select name="program_studi_id" required>
                @foreach ($daftarProgramStudi as $prodi)
                    <option
                        value="{{ $prodi['id'] }}"
                        {{ old('program_studi_id', $mahasiswa['program_studi_id']) == $prodi['id'] ? 'selected' : '' }}
                    >
                        {{ $prodi['nama_program_studi'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('mahasiswa.index') }}">Kembali</a>

    </form>

</body>
</html>