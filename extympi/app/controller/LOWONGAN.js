Ext.define('YMPI.controller.LOWONGAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_lowongan'],
	models: ['m_lowongan'],
	stores: ['s_lowongan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listlowongan',
		selector: 'Listlowongan'
	}],


	init: function(){
		this.control({
			'Listlowongan': {
				'afterrender': this.lowonganAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listlowongan button[action=create]': {
				click: this.createRecord
			},
			'Listlowongan button[action=delete]': {
				click: this.deleteRecord
			}
		});
	},
	
	lowonganAfterRender: function(){
		var lowonganStore = this.getListlowongan().getStore();
		lowonganStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_lowongan');
		var r = Ext.ModelManager.create({
			GELLOW		: '',
			TANGGAL		: '',
			KETERANGAN	: ''
		}, model);
		this.getListlowongan().getStore().insert(0, r);
		this.getListlowongan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListlowongan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListlowongan().getStore();
		var selection = this.getListlowongan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: GELLOW = "'+selection.data.GELLOW+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	}
	
});