Ext.define('YMPI.model.m_unitkerja', {
	extend: 'Ext.data.Model',
	alias		: 'widget.unitkerjaModel',
	fields		: ['KODEUNIT','P_KODEUNIT','NAMAUNIT','NAMAUNIT_TREE','LEAF','LFT','RGT'
				   ,'SINGKATAN'],
	idProperty	: 'KODEUNIT'	
});