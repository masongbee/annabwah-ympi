Ext.define('YMPI.model.Karyawan', {
    extend: 'Ext.data.Model',
    alias		: 'widget.karyawanModel',
    fields		: ['ID', 'NIK', 'NAMAKAR'],
	idProperty	: 'NIK'
});
