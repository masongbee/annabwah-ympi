Ext.define('YMPI.controller.MKINERJA',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_mkinerja'],
	models: ['m_mkinerja'],
	stores: ['s_mkinerja'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listmkinerja',
		selector: 'Listmkinerja'
	}],


	init: function(){
		this.control({
			'Listmkinerja': {
				'afterrender': this.mkinerjaAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listmkinerja button[action=create]': {
				click: this.createRecord
			},
			'Listmkinerja button[action=delete]': {
				click: this.deleteRecord
			}
		});
	},
	
	mkinerjaAfterRender: function(){
		var mkinerjaStore = this.getListmkinerja().getStore();
		mkinerjaStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_mkinerja');
		var r = Ext.ModelManager.create({
			KODE 			: ''
			,NAMAPENILAIAN 	: ''
			,TGLMULAI 		: ''
			,TGLSAMPAI 		: ''
		}, model);
		this.getListmkinerja().getStore().insert(0, r);
		this.getListmkinerja().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListmkinerja().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListmkinerja().getStore();
		var selection = this.getListmkinerja().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODE = "'+selection.data.KODE+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	}
	
});