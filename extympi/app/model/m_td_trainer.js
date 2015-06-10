Ext.define('YMPI.model.m_td_trainer', {
	extend: 'Ext.data.Model',
	alias		: 'widget.td_trainerModel',
	fields		: ['TDTRAINER_ID','TDTRAINER_KODE','TDTRAINER_NAMA','TDTRAINER_KETERANGAN','TDTRAINER_CREATED_BY','TDTRAINER_CREATED_DATE','TDTRAINER_UPDATED_BY','TDTRAINER_UPDATED_DATE','TDTRAINER_REVISED'],
	idProperty	: 'TDTRAINER_ID'	
});