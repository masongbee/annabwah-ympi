Ext.define('YMPI.model.m_periodegaji', {
	extend: 'Ext.data.Model',
	alias		: 'widget.periodegajiModel',
	fields		: ['BULAN','TGLMULAI','TGLSAMPAI','POSTING','TGLPOSTING','USERNAME'],
	idProperty	: 'BULAN'	
});