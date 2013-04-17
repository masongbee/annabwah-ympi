Ext.define('YMPI.controller.POSTPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.Presensi','PROSES.periodegaji'],
	models: ['Presensi','periodegaji'],
	stores: ['Presensi','periodegaji'],
	
	//requires: ['YMPI.view.PROSES.Presensi'],
	
	refs: [{
		ref: 'periodegaji',
		selector: 'periodegaji'
	}],
	
	init: function(){
		this.control({
			'periodegaji button[action=Posting]': {
				click: this.hitungPresensi
			}
		});
	},
	
	hitungPresensi : function() {
		console.info('POSTING PRESENSI');
		
	}
});