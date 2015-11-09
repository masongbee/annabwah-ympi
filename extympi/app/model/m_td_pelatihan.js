Ext.define('YMPI.model.m_td_pelatihan', {
	extend: 'Ext.data.Model',
	alias		: 'widget.td_pelatihanModel',
	fields		: ['NIK'
		,'NAMAKAR'
		,'KODETRAINING'
		,'NAMATRAINING'
		,'TAHUN'
		,'TEMPAT'
		,'TGLMULAI'
		,'TGLSAMPAI'
		,'PENYELENGGARA'
		,'KETERANGAN'],
	idProperty	: 'NIK'	
});