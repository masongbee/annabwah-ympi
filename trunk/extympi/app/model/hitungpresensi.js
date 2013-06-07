Ext.define('YMPI.model.hitungpresensi', {
    extend: 'Ext.data.Model',
    alias		: 'widget.hitungpresensiModel',
    fields		: ['NIK', 'BULAN', 'TANGGAL', 'JENISABSEN','HARIKERJA', 'JAMKERJA', 'JAMLEMBUR', 'JAMKURANG', 'JAMBOLOS','EXTRADAY', 'TERLAMBAT', 'PLGLBHAWAL', 'USERNAME'],
	idProperty	: 'NIK'
});
