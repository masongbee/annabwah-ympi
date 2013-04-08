Ext.define('YMPI.model.Absensi', {
    extend: 'Ext.data.Model',
    alias		: 'widget.AbsensiModel',
    fields		: ['NIK', 'TJMASUK', 'TJKELUAR', 'ASALDATA', 'POSTING', 'USERNAME'],
	idProperty	: 'NIK'
});
