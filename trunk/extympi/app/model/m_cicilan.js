Ext.define('YMPI.model.m_cicilan', {
	extend: 'Ext.data.Model',
	alias		: 'widget.cicilanModel',
	fields		: ['NOCICILAN','NIK','TGLAMBIL','RPPOKOK','LAMACICILAN','RPCICILAN','RPCICILANAKHIR','KEPERLUAN','BULANMULAI','LUNAS','TGLLUNAS'],
	idProperty	: 'NOCICILAN'	
});