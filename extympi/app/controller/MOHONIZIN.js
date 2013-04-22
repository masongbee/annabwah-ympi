Ext.define('YMPI.controller.MOHONIZIN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.permohonanijin'],
	models: ['permohonanijin'],
	stores: ['permohonanijin'],
	
	//requires: ['YMPI.view.TRANSAKSI.permohonanijin'],
	
	refs: [{
		ref: 'permohonanijin',
		selector: 'permohonanijin'
	}],
	
	init: function(){
		this.control({
			'permohonanijin': {
				'afterrender': this.LoadStore
			},
			'permohonanijin button[action=create]': {
				click: this.createRecordGroup
			},
			'permohonanijin button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getPermohonanijinStore = this.getPermohonanijin().getStore();
		getPermohonanijinStore.load();
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.permohonanijin');
		var grid 		= this.getPermohonanijin();
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
		var getPermohonanijin = this.getPermohonanijin(),
			getPermohonanijinStore = getPermohonanijin.getStore();
		var selection = this.getPermohonanijin().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getPermohonanijin.down('#btndelete').setDisabled(true);
			    	
			    	getPermohonanijinStore.remove(selection);
			    	getPermohonanijinStore.sync();
			    }
			});
			
		}
	}
	
});