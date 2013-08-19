Ext.define('YMPI.model.m_tkeluarga', {
	extend: 'Ext.data.Model',
	alias		: 'widget.tkeluargaModel',
	fields		: ['VALIDFROM','VALIDTO','NOURUT','BULANMULAI','BULANSAMPAI','STATUSKEL2','NIK','GRADE','KODEJAB','UMURTO'
				   ,{name: 'PELAJAR', type: 'bool'}
				   ,'RPTKELUARGA','USERNAME']	
});