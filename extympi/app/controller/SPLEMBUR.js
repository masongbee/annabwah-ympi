Ext.define('YMPI.controller.SPLEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.lembur','TRANSAKSI.rencanalembur'],
	models: ['lembur','rencanalembur'],
	stores: ['lembur','rencanalembur'],
	
	//requires: ['YMPI.view.TRANSAKSI.lembur'],
	
	refs: [{
		ref: 'lembur',
		selector: 'lembur'
	},{
		ref: 'rencanalembur',
		selector: 'rencanalembur'
	}],
	
	init: function(){
		this.control({
			'lembur': {
				'afterrender': this.LoadStore
			},
			'rencanalembur button[action=Hitung]': {
				click: this.hitungPresensi
			},
			'lembur button[action=create]': {
				click: this.createRecordGroup
			},
			'lembur button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getLemburStore = this.getLembur().getStore();
		getLemburStore.load();
		var getRencanalemburStore = this.getRencanalembur().getStore();
		getRencanalemburStore.load();
	},
	
	hitungPresensi : function() {
		console.info('HITUNG PRESENSI');
		
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.lembur');
		var grid 		= this.getLembur();
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
		var getLembur = this.getLembur(),
			getLemburStore = getLembur.getStore();
		var selection = this.getLembur().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getLembur.down('#btndelete').setDisabled(true);
			    	
			    	getLemburStore.remove(selection);
			    	getLemburStore.sync();
			    }
			});
			
		}
	}
});