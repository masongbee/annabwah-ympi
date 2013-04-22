Ext.define('YMPI.controller.KOMPENCUTI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.kompensasicuti'],
	models: ['kompensasicuti'],
	stores: ['kompensasicuti'],
	
	//requires: ['YMPI.view.TRANSAKSI.kompensasicuti'],
	
	refs: [{
		ref: 'kompensasicuti',
		selector: 'kompensasicuti'
	}],
	
	init: function(){
		this.control({
			'kompensasicuti': {
				'afterrender': this.LoadStore
			},
			'kompensasicuti button[action=create]': {
				click: this.createRecordGroup
			},
			'kompensasicuti button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getKompensasicutiStore = this.getKompensasicuti().getStore();
		getKompensasicutiStore.load();
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.kompensasicuti');
		var grid 		= this.getKompensasicuti();
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
		var getKompensasicuti = this.getKompensasicuti(),
			getKompensasicutiStore = getKompensasicuti.getStore();
		var selection = this.getKompensasicuti().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getKompensasicuti.down('#btndelete').setDisabled(true);
			    	
			    	getKompensasicutiStore.remove(selection);
			    	getKompensasicutiStore.sync();
			    }
			});
			
		}
	}
	
});