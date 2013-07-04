Ext.define('YMPI.model.m_splembur', {
	extend: 'Ext.data.Model',
	alias		: 'widget.splemburModel',
	fields		: ['NOLEMBUR','KODEUNIT','TANGGAL','KEPERLUAN','NIKUSUL','NIKSETUJU','NIKDIKETAHUI','NIKPERSONALIA','TGLSETUJU','TGLPERSONALIA','USERNAME'],
	idProperty	: 'NOLEMBUR'	
});