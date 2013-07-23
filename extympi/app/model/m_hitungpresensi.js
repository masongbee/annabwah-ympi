Ext.define('YMPI.model.m_hitungpresensi', {
	extend: 'Ext.data.Model',
	alias		: 'widget.hitungpresensiModel',
	fields		: ['NIK','NAMA','BULAN',{
        name: 'TANGGAL',
        type: 'date',
        dateFormat: 'Y-m-d'
    },'JENISABSEN','HARIKERJA','JAMKERJA','JAMLEMBUR','JAMKURANG','JAMBOLOS','EXTRADAY','TERLAMBAT','PLGLBHAWAL','USERNAME','POSTING']	
});