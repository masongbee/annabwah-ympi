Ext.define('YMPI.model.m_kalenderlibur', {
	extend: 'Ext.data.Model',
	alias		: 'widget.kalenderliburModel',
	fields		: ['TANGGAL','JENISLIBUR','AGAMA','KETERANGAN','USERNAME'],
	idProperty	: 'TANGGAL'	
});