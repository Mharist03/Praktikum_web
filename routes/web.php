
<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/mahasiswa', function () {
    try {
        $pdo = DB::connection()->getPdo();

        $sql = 'SELECT m.nim, m.nama, m.email, m.usia,
                       p.nama_program_studi AS nama_prodi
                FROM mahasiswa AS m
                JOIN program_studi AS p
                    ON p.id = m.program_studi_id
                ORDER BY m.nim';

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $daftarMahasiswa = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $sqlProgramStudi = 'SELECT id, nama_program_studi
                            FROM program_studi
                            ORDER BY nama_program_studi';

        $statementProgramStudi = $pdo->prepare($sqlProgramStudi);
        $statementProgramStudi->execute();

        $daftarProgramStudi = $statementProgramStudi->fetchAll(
            \PDO::FETCH_ASSOC
        );

        return view('mahasiswa', compact(
            'daftarMahasiswa',
            'daftarProgramStudi'
        ));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Koneksi atau query basis data gagal. Periksa konfigurasi database.',
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
