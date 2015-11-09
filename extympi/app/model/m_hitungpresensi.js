Ext.define('YMPI.model.m_hitungpresensi', {
	extend: 'Ext.data.Model',
	alias		: 'widget.hitungpresensiModel',
	fields		: [{
        name: 'TANGGAL',
        type: 'date',
        dateFormat: 'Y-m-d'
    },'BULAN','NIK','NAMAKAR','NAMAUNIT','NAMAKEL','JENISABSEN','JAMKERJA','HARIKERJA','EXTRADAY','JENISLEMBUR'
    ,'JAMLEMBUR','SATLEMBUR','TERLAMBAT','PLGLBHAWAL','IZINPRIBADI','JAMKURANG','XPOTONG','USERNAME','POSTING'
    ,'JENISABSEN_NAMA']	
});