DROP TABLE IF EXISTS `lamaran`;
DROP TABLE IF EXISTS `kontrak`;
DROP TABLE IF EXISTS `pelamarlolos`;
DROP TABLE IF EXISTS `tahapseleksi`;
DROP TABLE IF EXISTS `pelamar`;

-- ----------------------------
-- Table structure for pelamar
-- ----------------------------
CREATE TABLE `pelamar` (
  `KTP` varchar(16) NOT NULL,
  `NAMAPELAMAR` varchar(30) DEFAULT NULL,
  `AGAMA` char(1) DEFAULT NULL COMMENT 'AGAMA diisi dengan:\r\n            I=Islam\r\n            P=Kristen Protestan\r\n            K=Kristen Katholik\r\n            H=Hindu\r\n            B=Budha\r\n            C=Konghucu\r\n            Kosong=Tidak beragama, atau semua agama.\r\n            ',
  `ALAMAT` varchar(50) DEFAULT NULL,
  `FOTO` varchar(40) DEFAULT NULL COMMENT 'Diisi dengan letak URL/Folder dan nama file Foto (bitmap)',
  `JENISKEL` char(1) DEFAULT NULL COMMENT 'L=Laki-laki\r\n            P=Perempuan',
  `JURUSAN` varchar(20) DEFAULT NULL,
  `KAWIN` char(1) DEFAULT NULL COMMENT 'Status perkawinan, dimana:\r\n            -K=Sudah Kawin\r\n            -B=Belum Kawin\r\n            -D=Duda\r\n            J=Janda',
  `KOTA` varchar(50) DEFAULT NULL,
  `NAMASEKOLAH` varchar(20) DEFAULT NULL,
  `PENDIDIKAN` char(3) DEFAULT NULL COMMENT 'Tingkat Pendidikan (lulusan), yakni diisi:\r\n            SD=Sekolah Dasar\r\n            SMP=Sekolah Menengah Pertama\r\n            SMA=Sekolah Menengah Atas / Sekolah Menengah Kejuruan / SMK\r\n            S1=Strata 1 atau sederajat\r\n            S2=Strata 2 at',
  `TELEPON` varchar(15) DEFAULT NULL,
  `TGLLAHIR` date DEFAULT NULL,
  `TMPLAHIR` varchar(20) DEFAULT NULL,
  `STATUSPELAMAR` char(1) DEFAULT NULL COMMENT 'STATUSPELAMAR diisi "D" jika telah DITERIMA sebagai karyawan, pada proses penerimaan karyawan hasil seleksi.',
  PRIMARY KEY (`KTP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Master Data Pelamar dari tahun ke tahun. Pelamar yang mendaf';


-- ----------------------------
-- Table structure for tahapseleksi
-- ----------------------------
CREATE TABLE `tahapseleksi` (
  `KTP` varchar(16) NOT NULL,
  `GELLOW` varchar(5) NOT NULL,
  `KODEJAB` varchar(5) NOT NULL,
  `IDJAB` varchar(10) NOT NULL,
  `NOURUT` int(11) NOT NULL,
  `KODESELEKSI` varchar(2) NOT NULL,
  `LULUS` char(1) NOT NULL COMMENT 'P = Proses\r\nY = Lulus\r\nT = Tidak Lulus',
  `TANGGAL` date NOT NULL,
  PRIMARY KEY (`KTP`,`NOURUT`),
  KEY `tahapseleksi_ibfk_2` (`GELLOW`,`KODEJAB`,`IDJAB`),
  KEY `FK_REL_PELAMAR` (`KTP`,`NOURUT`) USING BTREE,
  CONSTRAINT `tahapseleksi_ibfk_1` FOREIGN KEY (`KTP`) REFERENCES `pelamar` (`KTP`),
  CONSTRAINT `tahapseleksi_ibfk_2` FOREIGN KEY (`GELLOW`, `KODEJAB`, `IDJAB`) REFERENCES `posisilowongan` (`GELLOW`, `KODEJAB`, `IDJAB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for pelamarlolos
-- ----------------------------
CREATE TABLE `pelamarlolos` (
  `GELLOW` varchar(5) NOT NULL COMMENT 'Gelombang lowongan yang dibuka. Setiap gelombang akan dibuka sekian posisi dengan kebutuhan lowongan dalam jumlah tertentu.',
  `NOURUT` int(11) NOT NULL,
  `KTP` varchar(16) NOT NULL,
  `NILAITES` decimal(8,2) DEFAULT NULL,
  `URUTAN` int(11) DEFAULT NULL,
  `LULUS` char(1) DEFAULT NULL,
  `CATATAN` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`GELLOW`,`NOURUT`,`KTP`),
  KEY `FK_REL_PELAMAR_LOLOS` (`KTP`),
  KEY `pelamarlolos_ibfk_1` (`KTP`,`NOURUT`),
  CONSTRAINT `pelamarlolos_ibfk_1` FOREIGN KEY (`KTP`, `NOURUT`) REFERENCES `tahapseleksi` (`KTP`, `NOURUT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Daftar pelamar lolos/lulus per tahapan seleksi.\r\n\r\n                                 -&#&';




-- ----------------------------
-- Table structure for kontrak
-- ----------------------------
CREATE TABLE `kontrak` (
  `GELLOW` varchar(5) NOT NULL COMMENT 'Gelombang lowongan yang dibuka. Setiap gelombang akan dibuka sekian posisi dengan kebutuhan lowongan dalam jumlah tertentu.',
  `NOURUT` int(11) NOT NULL,
  `KTP` varchar(16) NOT NULL,
  `POS_GELLOW` varchar(5) NOT NULL COMMENT 'Gelombang lowongan yang dibuka. Setiap gelombang akan dibuka sekian posisi dengan kebutuhan lowongan dalam jumlah tertentu.',
  `KODEJAB` varchar(5) NOT NULL COMMENT 'KODEJAB diisi dengan singkatan atau kode dari jabatan. Misalnya:\r\n            - GMFIN = GM Divisi Finance\r\n            - GMADM = GM Divisi Administration\r\n            - MGRHR = Manager Departemen HR\r\n            - MGRPC = Manager Departemen PC\r\n          ',
  `NIK` varchar(10) DEFAULT NULL,
  `NAMAKAR` varchar(50) DEFAULT NULL,
  `JENISKEL` char(1) DEFAULT NULL COMMENT 'L=Laki-laki\r\n            P=Perempuan',
  `TMPLAHIR` varchar(20) DEFAULT NULL,
  `TGLLAHIR` date DEFAULT NULL,
  `AGAMA` char(1) DEFAULT NULL COMMENT 'AGAMA diisi dengan:\r\n            I=Islam\r\n            P=Kristen Protestan\r\n            K=Kristen Katholik\r\n            H=Hindu\r\n            B=Budha\r\n            C=Konghucu\r\n            Kosong=Tidak beragama, atau semua agama.\r\n            ',
  `ALAMAT` varchar(50) DEFAULT NULL,
  `DESA` varchar(20) DEFAULT NULL,
  `RT` char(3) DEFAULT NULL,
  `RW` char(3) DEFAULT NULL,
  `KECAMATAN` varchar(20) DEFAULT NULL,
  `GRADE` char(2) DEFAULT NULL,
  `KOTA` varchar(50) DEFAULT NULL,
  `PENDIDIKAN` char(3) DEFAULT NULL COMMENT 'Tingkat Pendidikan (lulusan), yakni diisi:\r\n            SD=Sekolah Dasar\r\n            SMP=Sekolah Menengah Pertama\r\n            SMA=Sekolah Menengah Atas / Sekolah Menengah Kejuruan / SMK\r\n            S1=Strata 1 atau sederajat\r\n            S2=Strata 2 at',
  `JURUSAN` varchar(20) DEFAULT NULL,
  `KAWIN` char(1) DEFAULT NULL COMMENT 'Status perkawinan, dimana:\r\n            -K=Sudah Kawin\r\n            -B=Belum Kawin\r\n            -D=Duda\r\n            J=Janda',
  `STATUS` char(1) DEFAULT NULL COMMENT 'STATUS kekaryawanan, yakni:\r\n            T=Karyawan TETAP\r\n            K=Karyawan KONTRAK\r\n            C=Karyawan masa PERCOBAAN\r\n            P=Karyawan sudah Pensiun\r\n            H=Karyawan di-PHK\r\n            M=Karyawan sudah Meninggal\r\n            \r\n  ',
  `TELEPON` varchar(15) DEFAULT NULL,
  `TANGGUNGSPKK` char(1) DEFAULT NULL COMMENT 'TANGGUNGSPKK diisi dengan ''Y'' jika yang bersangkutan mendapatkan jaminan kesehatan lewat SPKK. Dan tidak diisi jika tidak ditanggung.',
  `KATPEKERJAAN` char(1) DEFAULT NULL,
  `FOTO` varchar(40) DEFAULT NULL COMMENT 'Diisi dengan letak URL/Folder dan nama file Foto (bitmap)',
  `RPUPAHPOKOK` decimal(12,2) DEFAULT NULL,
  `RPTJABATAN` decimal(12,2) DEFAULT NULL,
  `RPTPEKERJAAN` decimal(12,2) DEFAULT NULL,
  `RPTOTGAJI` decimal(12,2) DEFAULT NULL,
  `NOACCKAR` varchar(12) DEFAULT NULL,
  `NAMABANK` varchar(20) DEFAULT NULL,
  `TGLMULAI` date DEFAULT NULL,
  `TGLKONTRAK` date DEFAULT NULL,
  `LAMAKONTRAK` int(11) DEFAULT NULL,
  `TGLPOSTING` datetime DEFAULT NULL,
  `POSTING` char(1) DEFAULT NULL COMMENT 'POSTING diisi ''P'' jika data sudah diposting dan diisi kosong jika belum.',
  `USERNAME` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`GELLOW`,`NOURUT`,`KTP`,`POS_GELLOW`,`KODEJAB`),
  KEY `FK_REL_POSISI_KONTRAK` (`POS_GELLOW`,`KODEJAB`),
  CONSTRAINT `kontrak_ibfk_1` FOREIGN KEY (`POS_GELLOW`, `KODEJAB`) REFERENCES `posisilowongan` (`GELLOW`, `KODEJAB`),
  CONSTRAINT `kontrak_ibfk_2` FOREIGN KEY (`GELLOW`, `NOURUT`, `KTP`) REFERENCES `pelamarlolos` (`GELLOW`, `NOURUT`, `KTP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Berisi daftar data kontrak antara PELAMAR yang telah diterim';



-- ----------------------------
-- Table structure for lamaran
-- ----------------------------
CREATE TABLE `lamaran` (
  `KTP` varchar(16) NOT NULL,
  `GELLOW` varchar(5) NOT NULL,
  `KODEJAB` varchar(5) NOT NULL,
  `IDJAB` varchar(10) NOT NULL,
  `NOTES` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`KTP`,`GELLOW`,`KODEJAB`,`IDJAB`),
  KEY `FK_REL_PELAMAR_LAMARAN` (`KTP`) USING BTREE,
  KEY `FK_REL_POSISI_LAMARAN` (`GELLOW`,`KODEJAB`,`IDJAB`) USING BTREE,
  CONSTRAINT `lamaran_ibfk_2` FOREIGN KEY (`GELLOW`, `KODEJAB`, `IDJAB`) REFERENCES `posisilowongan` (`GELLOW`, `KODEJAB`, `IDJAB`),
  CONSTRAINT `lamaran_ibfk_1` FOREIGN KEY (`KTP`) REFERENCES `pelamar` (`KTP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






-- ----------------------------
-- Table structure for jenisseleksi
-- ----------------------------
DROP TABLE IF EXISTS `jenisseleksi`;
CREATE TABLE `jenisseleksi` (
  `KODESELEKSI` varchar(2) NOT NULL,
  `NAMASELEKSI` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`KODESELEKSI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Jenis-jenis seleksi, mulai dari pendaftaran hingga penerimaa';

-- ----------------------------
-- Records of jenisseleksi
-- ----------------------------
INSERT INTO `jenisseleksi` VALUES ('A', 'Melamar (sudah lolos seleksi adm)');
INSERT INTO `jenisseleksi` VALUES ('B', 'Interview Awal + Tes Tulis (by user)');
INSERT INTO `jenisseleksi` VALUES ('C', 'Psikotest');
INSERT INTO `jenisseleksi` VALUES ('D', 'Tes Kesehatan');
INSERT INTO `jenisseleksi` VALUES ('E', 'Cek Fisik dengan Dokter');
INSERT INTO `jenisseleksi` VALUES ('F', 'Interview Akhir (Diterima / Tidak)');
INSERT INTO `jenisseleksi` VALUES ('G', 'Dipindahkan ke Master Karyawan');






