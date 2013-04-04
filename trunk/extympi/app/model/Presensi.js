Ext.define('YMPI.model.Presensi', {
    extend: 'Ext.data.Model',
    alias		: 'widget.PresensiModel',
    fields		: ['NIK', 'TJMASUK', 'TJKELUAR', 'ASALDATA', 'POSTING', 'USERNAME'],
	idProperty	: 'NIK'
});
