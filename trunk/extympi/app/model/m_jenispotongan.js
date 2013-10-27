Ext.define('YMPI.model.m_jenispotongan', {
	extend: 'Ext.data.Model',
	alias		: 'widget.jenispotonganModel',
	fields		: ['KODEPOTONGAN','NAMAPOTONGAN','POSCETAK','NAMAPOTONGANALTERNATIF'],
	idProperty	: 'KODEPOTONGAN'	
});