Ext.define('YMPI.controller.TKINERJA',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_tkinerja'],
	models: ['m_tkinerja'],
	stores: ['s_tkinerja','s_karyawan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtkinerja',
		selector: 'Listtkinerja'
	}],


	init: function(){
		this.control({
			'Listtkinerja': {
				'afterrender': this.tkinerjaAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtkinerja button[action=create]': {
				click: this.createRecord
			},
			'Listtkinerja button[action=delete]': {
				click: this.deleteRecord
			}
		});
	},
	
	tkinerjaAfterRender: function(){
		var tkinerjaStore = this.getListtkinerja().getStore();
		var karyawanStore = this.getStore('s_karyawan');

		tkinerjaStore.load();
		karyawanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tkinerja');
		var r = Ext.ModelManager.create({
			NIK 		: ''
			,KODE 		: ''
			,NILAI 		: ''
			,CATATAN 	: ''
		}, model);
		this.getListtkinerja().getStore().insert(0, r);
		this.getListtkinerja().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtkinerja().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtkinerja().getStore();
		var selection = this.getListtkinerja().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NIK = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	}
	
});