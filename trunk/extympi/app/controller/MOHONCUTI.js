Ext.define('YMPI.controller.MOHONCUTI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.permohonancuti','TRANSAKSI.rinciancuti'],
	models: ['permohonancuti','rinciancuti'],
	stores: ['permohonancuti','rinciancuti'],
	
	//requires: ['YMPI.view.TRANSAKSI.permohonancuti'],
	
	refs: [{
		ref: 'permohonancuti',
		selector: 'permohonancuti'
	},{
		ref: 'rinciancuti',
		selector: 'rinciancuti'
	}],
	
	init: function(){
		this.control({
			'permohonancuti': {
				'afterrender': this.LoadStore
			},
			'rinciancuti button[action=Hitung]': {
				click: this.hitungPresensi
			},
			'permohonancuti button[action=create]': {
				click: this.createRecordGroup
			},
			'permohonancuti button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getPermohonancutiStore = this.getPermohonancuti().getStore();
		getPermohonancutiStore.load();
		var getRinciancutiStore = this.getRinciancuti().getStore();
		getRinciancutiStore.load();
	},
	
	hitungPresensi : function() {
		console.info('HITUNG PRESENSI');
		
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.permohonancuti');
		var grid 		= this.getPermohonancuti();
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
		var getPermohonancuti = this.getPermohonancuti(),
			getPermohonancutiStore = getPermohonancuti.getStore();
		var selection = this.getPermohonancuti().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getPermohonancuti.down('#btndelete').setDisabled(true);
			    	
			    	getPermohonancutiStore.remove(selection);
			    	getPermohonancutiStore.sync();
			    }
			});
			
		}
	}
});