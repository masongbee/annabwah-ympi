Ext.define('YMPI.model.m_mohonizin', {
	extend: 'Ext.data.Model',
	alias		: 'widget.mohonizinModel',
	fields		: ['NOIJIN','NIK','JENISABSEN','TANGGAL','JAMDARI','JAMSAMPAI','KEMBALI','DIAGNOSA','TINDAKAN','ANJURAN','PETUGASKLINIK','NIKATASAN1','NIKPERSONALIA','NIKGA','NIKDRIVER','NIKSECURITY','USERNAME'],
	idProperty	: 'NOIJIN'	
});