ALTER TABLE `hitungpresensi` ADD COLUMN `ID`  int(11) NOT NULL DEFAULT 0 FIRST ;



UPDATE hitungpresensi t1
JOIN (
	SELECT  @a:=@a+1 serial_number, 
					NIK
					,BULAN
					,TANGGAL
					,JENISABSEN
					,HARIKERJA
					,JAMKERJA
					,JENISLEMBUR
					,JAMLEMBUR
					,SATLEMBUR
					,JAMBOLOS
					,USERNAME
					,POSTING
					,EXTRADAY
					,TERLAMBAT
					,PLGLBHAWAL
					,IZINPRIBADI
					,JAMKURANG
					,XPOTONG
	FROM    hitungpresensi,
					(SELECT @a:= 0) AS a
) t2 ON(t2.NIK = t1.NIK
					AND t2.BULAN = t1.BULAN
					AND t2.TANGGAL = t1.TANGGAL
					AND t2.JENISABSEN = t1.JENISABSEN)
SET t1.ID = t2.serial_number;



