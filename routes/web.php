
<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Menampilkan, mencari, dan memfilter data mahasiswa
Route::get('/mahasiswa', function (\Illuminate\Http\Request $request) {
    try {
        $pdo = DB::connection()->getPdo();

        $keyword = trim($request->query('keyword', ''));
        $programStudiId = $request->query('program_studi_id', '');

        $sql = '
            SELECT
                m.id,
                m.nim,
                m.nama,
                m.email,
                m.usia,
                m.program_studi_id,
                p.nama_program_studi AS nama_prodi
            FROM mahasiswa m
            LEFT JOIN program_studi p
                ON m.program_studi_id = p.id
            WHERE 1 = 1
        ';

        $parameter = [];

        if ($keyword !== '') {
            $sql .= '
                AND (
                    m.nim LIKE :nim
                    OR m.nama LIKE :nama
                    OR m.email LIKE :email
                )
            ';

            $cari = '%' . $keyword . '%';

            $parameter['nim'] = $cari;
            $parameter['nama'] = $cari;
            $parameter['email'] = $cari;
        }

        if ($programStudiId !== '') {
            $sql .= ' AND m.program_studi_id = :program_studi_id';
            $parameter['program_studi_id'] = $programStudiId;
        }

        $sql .= ' ORDER BY m.id ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($parameter);

        $mahasiswa = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $prodiStmt = $pdo->prepare(
            'SELECT id, nama_program_studi
             FROM program_studi
             ORDER BY nama_program_studi'
        );

        $prodiStmt->execute();

        $daftarProgramStudi = $prodiStmt->fetchAll(
            \PDO::FETCH_ASSOC
        );

        return view('mahasiswa', compact(
            'mahasiswa',
            'daftarProgramStudi',
            'keyword',
            'programStudiId'
        ));

    } catch (\Throwable $error) {
        report($error);

        return response(
            'Data mahasiswa gagal dimuat. Periksa koneksi database.',
            500
        );
    }
})->name('mahasiswa.index');

Route::post('/mahasiswa', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nim' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'usia' => 'required|integer|min:15|max:100',
        'program_studi_id' => 'required|integer|exists:program_studi,id',
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        $sqlCek = 'SELECT COUNT(*) FROM mahasiswa
                   WHERE nim = :nim OR email = :email';

        $statement = $pdo->prepare($sqlCek);
        $statement->execute([
            'nim' => $validated['nim'],
            'email' => $validated['email'],
        ]);

        if ((int) $statement->fetchColumn() > 0) {
            return back()
                ->withInput()
                ->with('gagal', 'NIM atau email sudah digunakan.');
        }

        $sqlInsert = 'INSERT INTO mahasiswa
                      (nim, nama, email, usia, program_studi_id)
                      VALUES
                      (:nim, :nama, :email, :usia, :program_studi_id)';

        $statement = $pdo->prepare($sqlInsert);
        $statement->execute([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'usia' => $validated['usia'],
            'program_studi_id' => $validated['program_studi_id'],
        ]);

        return redirect()
            ->route('mahasiswa.index')
            ->with('sukses', 'Data mahasiswa berhasil ditambahkan.');
    } catch (\Throwable $error) {
        report($error);

        return back()
            ->withInput()
            ->with('gagal', 'Data gagal disimpan. Periksa koneksi dan data database.');
    }
})->name('mahasiswa.store');

// Membuka halaman edit mahasiswa berdasarkan ID
Route::get('/mahasiswa/{id}/edit', function (int $id) {
    try {
        $pdo = DB::connection()->getPdo();

        $ambilMahasiswa = $pdo->prepare(
            'SELECT id, nim, nama, email, usia, program_studi_id
             FROM mahasiswa
             WHERE id = :id'
        );

        $ambilMahasiswa->execute(['id' => $id]);

        $mahasiswa = $ambilMahasiswa->fetch(\PDO::FETCH_ASSOC);

        if (!$mahasiswa) {
            return redirect()
                ->route('mahasiswa.index')
                ->with('gagal', 'Data mahasiswa tidak ditemukan.');
        }

        $ambilProdi = $pdo->prepare(
            'SELECT id, nama_program_studi
             FROM program_studi
             ORDER BY nama_program_studi'
        );

        $ambilProdi->execute();

        $daftarProgramStudi = $ambilProdi->fetchAll(
            \PDO::FETCH_ASSOC
        );

        return view('mahasiswa-edit', compact(
            'mahasiswa',
            'daftarProgramStudi'
        ));

    } catch (\Throwable $error) {
        report($error);

        return redirect()
            ->route('mahasiswa.index')
            ->with('gagal', 'Data gagal dibuka untuk diubah.');
    }
})->name('mahasiswa.edit');
// Memproses perubahan data mahasiswa
Route::put('/mahasiswa/{id}', function (
    \Illuminate\Http\Request $request,
    int $id
) {
    $data = $request->validate([
        'nim' => ['required', 'string', 'max:12'],
        'nama' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:100'],
        'usia' => ['required', 'integer', 'min:15', 'max:100'],
        'program_studi_id' => [
            'required',
            'integer',
            'exists:program_studi,id'
        ],
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        // Memeriksa NIM atau email yang sudah digunakan mahasiswa lain
        $cek = $pdo->prepare(
            'SELECT COUNT(*) FROM mahasiswa
             WHERE (nim = :nim OR email = :email)
             AND id <> :id'
        );

        $cek->execute([
            'nim' => $data['nim'],
            'email' => $data['email'],
            'id' => $id,
        ]);

        if ((int) $cek->fetchColumn() > 0) {
            return back()->withInput()->with(
                'gagal',
                'NIM atau email sudah digunakan mahasiswa lain.'
            );
        }

        // Memperbarui data mahasiswa
        $ubah = $pdo->prepare(
            'UPDATE mahasiswa
             SET nim = :nim,
                 nama = :nama,
                 email = :email,
                 usia = :usia,
                 program_studi_id = :program_studi_id
             WHERE id = :id'
        );

        $ubah->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'usia' => $data['usia'],
            'program_studi_id' => $data['program_studi_id'],
            'id' => $id,
        ]);

        return redirect()
            ->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil diperbarui.');

    } catch (\Throwable $error) {
        report($error);

        return back()->withInput()->with(
            'gagal',
            'Data gagal diperbarui. Periksa koneksi dan data.'
        );
    }
})->name('mahasiswa.update');
// Menghapus data mahasiswa berdasarkan ID
Route::delete('/mahasiswa/{id}', function (int $id) {
    try {
        $pdo = DB::connection()->getPdo();

        $hapus = $pdo->prepare(
            'DELETE FROM mahasiswa WHERE id = :id'
        );

        $hapus->execute(['id' => $id]);

        if ($hapus->rowCount() === 0) {
            return redirect()
                ->route('mahasiswa.index')
                ->with('gagal', 'Data mahasiswa tidak ditemukan.');
        }

        return redirect()
            ->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil dihapus.');

    } catch (\Throwable $error) {
        report($error);

        return redirect()
            ->route('mahasiswa.index')
            ->with('gagal', 'Data mahasiswa gagal dihapus.');
    }
})->name('mahasiswa.destroy');
