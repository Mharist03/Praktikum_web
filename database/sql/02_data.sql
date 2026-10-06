USE praktikum_web_2401020149;

-- =========================================
-- INSERT DATA PROGRAM STUDI
-- =========================================

INSERT INTO program_studi (nama_prodi)
VALUES
('Teknik Informatika'),
('Sistem Informasi');


-- =========================================
-- INSERT DATA MAHASISWA
-- =========================================

INSERT INTO mahasiswa
    (nim, nama, email, usia, program_studi_id)
VALUES
    ('2401020001', 'Andi Saputra', 'andi@gmail.com', 20, 1),
    ('2401020002', 'Siti Rahma', 'siti@gmail.com', 19, 1),
    ('2401020003', 'Budi Pratama', 'budi@gmail.com', 21, 2),
    ('2401020099', 'Data Sementara', 'sementara@gmail.com', 18, 2);


-- =========================================
-- UPDATE DATA
-- =========================================

UPDATE mahasiswa
SET email = 'andi.saputra@gmail.com'
WHERE nim = '2401020001';


-- =========================================
-- DELETE DATA SEMENTARA
-- =========================================

DELETE FROM mahasiswa
WHERE nim = '2401020099';


-- =========================================
-- SELECT JOIN
-- =========================================

SELECT
    m.nim,
    m.nama,
    m.email,
    m.usia,
    p.nama_prodi
FROM mahasiswa AS m
JOIN program_studi AS p
    ON p.id = m.program_studi_id
ORDER BY m.nim;


-- =========================================
-- PENGUJIAN PRIMARY KEY / UNIQUE
-- Data berikut sengaja ditolak karena NIM
-- sudah digunakan.
-- =========================================

-- INSERT INTO mahasiswa
--     (nim, nama, email, usia, program_studi_id)
-- VALUES
--     ('2401020001', 'Mahasiswa Duplikat',
--      'duplikat@gmail.com', 20, 1);


-- =========================================
-- PENGUJIAN FOREIGN KEY
-- Data berikut sengaja ditolak karena
-- program_studi_id = 99 tidak tersedia.
-- =========================================

-- INSERT INTO mahasiswa
--     (nim, nama, email, usia, program_studi_id)
-- VALUES
--     ('2401020099', 'Uji Foreign Key',
--      'uji_fk@gmail.com', 20, 99);


-- =========================================
-- CEK JUMLAH DATA MAHASISWA
-- =========================================

SELECT COUNT(*) AS jumlah_mahasiswa
FROM mahasiswa;