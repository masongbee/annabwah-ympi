Ext.define('YMPI.model.m_jenistambahan', {
	extend: 'Ext.data.Model',
	alias		: 'widget.jenistambahanModel',
	fields		: ['KODEUPAH','NAMAUPAH','POSCETAK','NAMAUPAHALTERNATIF'],
	idProperty	: 'KODEUPAH'	
});