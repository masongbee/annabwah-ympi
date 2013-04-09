Ext.define('YMPI.controller.IMPORTPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.Presensi','PROSES.ImportPresensi','PROSES.Testku'],
	models: ['Presensi'],
	stores: ['Presensi'],
	
	//requires: ['YMPI.view.PROSES.Presensi'],
	
	refs: [{
		ref: 'Presensi',
		selector: 'Presensi'
	},{
		ref: 'Presensi',
		selector: 'Presensi'
	}],
	
	init: function(){
		this.control({
			'Presensi': {
				'afterrender': this.LoadStore
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getPresensiStore = this.getPresensi().getStore();
		getPresensiStore.load();
	}
});