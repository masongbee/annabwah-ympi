Ext.define('YMPI.controller.JENISABSEN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.Absensi'],
	models: ['Absensi'],
	stores: ['Absensi'],
	
	refs: [{
		ref: 'Absensi',
		selector: 'Absensi'
	},{
		ref: 'Absensi',
		selector: 'Absensi'
	}],
	
	init: function(){
		this.control({
			'Absensi': {
				'afterrender': this.LoadStore
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getAbsensiStore = this.getAbsensi().getStore();
		getAbsensiStore.load();
	}
});