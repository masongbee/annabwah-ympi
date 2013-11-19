Ext.define('YMPI.model.m_permohonancuti', {
	extend: 'Ext.data.Model',
	alias		: 'widget.permohonancutiModel',
	fields		: ['NOCUTI','KODEUNIT','CUTIMASAL','NIKATASAN1','NIKATASAN2','NIKATASAN3','NIKHR','TGLATASAN1','TGLATASAN2','TGLATASAN3','TGLHR','STATUSCUTI','USERNAME'],
	idProperty	: 'NOCUTI'	
});