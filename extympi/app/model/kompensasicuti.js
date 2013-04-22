Ext.define('YMPI.model.kompensasicuti', {
    extend: 'Ext.data.Model',
    alias		: 'widget.kompensasicutiModel',
    fields		: ['NIK', 'TAHUN', 'TANGGAL', 'SISACUTI', 'RPKOMPEN', 'BULAN', 'POSTING'],
	idProperty	: 'NOIJIN'
});
