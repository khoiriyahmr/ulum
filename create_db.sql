

-- Membuat tabel siswa
CREATE TABLE siswa (
    id_siswa INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    kelas VARCHAR(50) NOT NULL,
    nisn VARCHAR(50) NOT NULL,
    nilai_raport FLOAT,
    nilai_ekstrakurikuler FLOAT,
    nilai_prestasi FLOAT,
    nilai_absensi FLOAT
);

-- Membuat tabel kriteria
CREATE TABLE kriteria (
    id_kriteria INT AUTO_INCREMENT PRIMARY KEY,
    nama_kriteria VARCHAR(255) NOT NULL,
    bobot FLOAT NOT NULL
);

-- Membuat tabel penilaian
CREATE TABLE penilaian (
    id_penilaian INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT,
    id_kriteria INT,
    nilai FLOAT,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa),
    FOREIGN KEY (id_kriteria) REFERENCES kriteria(id_kriteria)
);

-- Membuat tabel matrik_perbandingan
CREATE TABLE matrik_perbandingan (
    kriteria1 INT,
    kriteria2 INT,
    nilai FLOAT,
    FOREIGN KEY (kriteria1) REFERENCES kriteria(id_kriteria),
    FOREIGN KEY (kriteria2) REFERENCES kriteria(id_kriteria)
);

-- Membuat tabel hasil
CREATE TABLE hasil (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT,
    nilai_akhir FLOAT,
    ranking INT,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa)
);

-- Membuat tabel periode
CREATE TABLE periode (
    id_periode INT AUTO_INCREMENT PRIMARY KEY,
    tahun YEAR NOT NULL,
    status ENUM('Aktif', 'Nonaktif') NOT NULL
);

CREATE TABLE alternatif (
    id_alternatif INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255),
    kelas INT,
    nilai_raport DECIMAL(5,2),
    extrakurikuler DECIMAL(5,2),
    prestasi DECIMAL(5,2),
    absensi DECIMAL(5,2)
);

CREATE TABLE perbandingan_alternatif_raport (
    id_perbandingan INT AUTO_INCREMENT PRIMARY KEY,
    alternatif1_id INT,
    alternatif2_id INT,
    nilai DECIMAL(5,2),
    FOREIGN KEY (alternatif1_id) REFERENCES alternatif(id_alternatif),
    FOREIGN KEY (alternatif2_id) REFERENCES alternatif(id_alternatif)
);
CREATE TABLE perbandingan_alternatif_ekstrakurikuler (
    id_perbandingan INT AUTO_INCREMENT PRIMARY KEY,
    alternatif1_id INT,
    alternatif2_id INT,
    nilai FLOAT,
    FOREIGN KEY (alternatif1_id) REFERENCES alternatif(id_alternatif),
    FOREIGN KEY (alternatif2_id) REFERENCES alternatif(id_alternatif)
);
CREATE TABLE perbandingan_alternatif_prestasi (
    id_perbandingan INT AUTO_INCREMENT PRIMARY KEY,
    alternatif1_id INT,
    alternatif2_id INT,
    nilai FLOAT,
    FOREIGN KEY (alternatif1_id) REFERENCES alternatif(id_alternatif),
    FOREIGN KEY (alternatif2_id) REFERENCES alternatif(id_alternatif)
);
CREATE TABLE perbandingan_alternatif_absensi (
    id_perbandingan INT AUTO_INCREMENT PRIMARY KEY,
    alternatif1_id INT,
    alternatif2_id INT,
    nilai FLOAT,
    FOREIGN KEY (alternatif1_id) REFERENCES alternatif(id_alternatif),
    FOREIGN KEY (alternatif2_id) REFERENCES alternatif(id_alternatif)
);
