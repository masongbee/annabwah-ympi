Ext.define('YMPI.controller.IMPORTPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.Presensi','PROSES.ImportPresensi'],
	models: ['Presensi'],
	stores: ['Presensi'],
	
	//requires: ['YMPI.view.PROSES.Presensi'],
	
	refs: [{
		ref: 'Presensi',
		selector: 'Presensi'
	}],
	
	init: function(){
		this.control({
			'Presensi': {
				'afterrender': this.LoadStore
			},
			'Presensi button[action=create]': {
				click: this.createRecordGroup
			},
			'Presensi button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getPresensiStore = this.getPresensi().getStore();
		getPresensiStore.load();
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.Presensi');
		var grid 		= this.getPresensi();
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
		var getPresensi = this.getPresensi(),
			getPresensiStore = getPresensi.getStore();
		var selection = this.getPresensi().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getPresensi.down('#btndelete').setDisabled(true);
			    	
			    	getPresensiStore.remove(selection);
			    	getPresensiStore.sync();
			    }
			});
			
		}
	}
	
});