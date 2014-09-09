Ext.define('YMPI.model.m_permohonancuti', {
	extend: 'Ext.data.Model',
	alias		: 'widget.permohonancutiModel',
	fields		: ['NOCUTI','KODEUNIT','NIKATASAN1','NAMAATASAN1','KODEUNIT_ATASAN1','NIKATASAN2','NAMAATASAN2','KODEUNIT_ATASAN2'
				   ,'NIKATASAN3','NIKHR','NAMAHR'
				   ,{
						name: 'TGLATASAN1',
						type: 'date',
						dateFormat: 'Y-m-d'
					}
				   ,'TGLATASAN2','TGLATASAN3'
				   ,'TGLHR','STATUSCUTI','USERNAME'],
	idProperty	: 'NOCUTI'	
});