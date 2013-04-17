Ext.define('YMPI.model.hitungpresensi', {
    extend: 'Ext.data.Model',
    alias		: 'widget.hitungpresensiModel',
    fields		: ['NIK', 'BULAN', 'TANGGAL', 'JENISABSEN', 'JAMKERJA', 'JAMLEMBUR', 'JAMKURANG', 'JAMBOLOS', 'TERLAMBAT', 'PLGLBHAWAL', 'USERNAME'],
	idProperty	: 'NIK'
});
