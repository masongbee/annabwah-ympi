Ext.define('YMPI.model.m_hitungpresensi', {
	extend: 'Ext.data.Model',
	alias		: 'widget.hitungpresensiModel',
	fields		: [{
        name: 'TANGGAL',
        type: 'date',
        dateFormat: 'Y-m-d'
    },'BULAN','NIK','NAMAKAR','NAMAUNIT','NAMAKEL','JENISABSEN','JAMKERJA','HARIKERJA','JENISLEMBUR','JAMLEMBUR','SATLEMBUR','JAMKURANG','EXTRADAY','TERLAMBAT','PLGLBHAWAL','IZINPRIBADI','USERNAME','POSTING']	
});