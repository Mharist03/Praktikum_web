
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>

    {{-- Pesan pemberitahuan --}}
    @if (session('berhasil'))
        <p style="color: green;">{{ session('berhasil') }}</p>
    @endif

    @if (session('sukses'))
        <p style="color: green;">{{ session('sukses') }}</p>
    @endif

    @if (session('gagal'))
        <p style="color: red;">{{ session('gagal') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <hr>

    <h2>Tambah Mahasiswa</h2>

    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf

        <p>
            <label for="nim">NIM:</label><br>
            <input type="text" id="nim" name="nim"
                   value="{{ old('nim') }}" maxlength="12" required>
        </p>

        <p>
            <label for="nama">Nama:</label><br>
            <input type="text" id="nama" name="nama"
                   value="{{ old('nama') }}" required>
        </p>

        <p>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}" required>
        </p>

        <p>
            <label for="usia">Usia:</label><br>
            <input type="number" id="usia" name="usia"
                   value="{{ old('usia') }}" min="15" max="100" required>
        </p>

        <p>
            <label for="program_studi_id">Program Studi:</label><br>
            <select id="program_studi_id" name="program_studi_id" required>
                <option value="">-- Pilih Program Studi --</option>

                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ old('program_studi_id') == $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_program_studi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Mahasiswa</button>
    </form>

    <hr>

    <h2>Pencarian dan Filter Mahasiswa</h2>

    <form action="{{ route('mahasiswa.index') }}" method="GET">
        <p>
            <label for="keyword">Cari NIM, Nama, atau Email:</label><br>
            <input type="text" id="keyword" name="keyword"
                   value="{{ $keyword }}">
        </p>

        <p>
            <label for="filter_prodi">Filter Program Studi:</label><br>
            <select id="filter_prodi" name="program_studi_id">
                <option value="">-- Semua Program Studi --</option>

                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ (string) $programStudiId === (string) $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_program_studi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Cari dan Filter</button>
        <a href="{{ route('mahasiswa.index') }}">Reset</a>
    </form>

    <hr>

    <h2>Daftar Mahasiswa</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Usia</th>
                <th>Program Studi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($mahasiswa as $mhs)
                <tr>
                    <td>{{ $mhs['id'] }}</td>
                    <td>{{ $mhs['nim'] }}</td>
                    <td>{{ $mhs['nama'] }}</td>
                    <td>{{ $mhs['email'] }}</td>
                    <td>{{ $mhs['usia'] }}</td>
                    <td>{{ $mhs['nama_prodi'] }}</td>

                    <td>
                        <a href="{{ route('mahasiswa.edit', $mhs['id']) }}">
                            Edit
                        </a>

                        |

                        <form action="{{ route('mahasiswa.destroy', $mhs['id']) }}"
                              method="POST"
                              style="display: inline;"
                              onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Data mahasiswa tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
