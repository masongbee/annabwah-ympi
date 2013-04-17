Ext.define('YMPI.controller.HITPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.hitungpresensi','PROSES.periodegaji'],
	models: ['hitungpresensi','periodegaji'],
	stores: ['hitungpresensi','periodegaji'],
	
	//requires: ['YMPI.view.PROSES.hitungpresensi'],
	
	refs: [{
		ref: 'hitungpresensi',
		selector: 'hitungpresensi'
	},{
		ref: 'periodegaji',
		selector: 'periodegaji'
	}],
	
	init: function(){
		this.control({
			'hitungpresensi': {
				'afterrender': this.LoadStore
			},
			'periodegaji button[action=Hitung]': {
				click: this.hitungPresensi
			},
			'hitungpresensi button[action=create]': {
				click: this.createRecordGroup
			},
			'hitungpresensi button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getHitungpresensiStore = this.getHitungpresensi().getStore();
		getHitungpresensiStore.load();
		var getPeriodegajiStore = this.getPeriodegaji().getStore();
		getPeriodegajiStore.load();
	},
	
	hitungPresensi : function() {
		console.info('HITUNG PRESENSI');
		
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.hitungpresensi');
		var grid 		= this.getHitungpresensi();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			GROUP_ID	: 0,
		    GROUP_NAME	: '',
		    GROUP_DESC	: ''
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordGroup: function(dataview, selections){
		var getHitungpresensi = this.getHitungpresensi(),
			getHitungpresensiStore = getHitungpresensi.getStore();
		var selection = this.getHitungpresensi().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getHitungpresensi.down('#btndelete').setDisabled(true);
			    	
			    	getHitungpresensiStore.remove(selection);
			    	getHitungpresensiStore.sync();
			    }
			});
			
		}
	}
});