Ext.define('YMPI.controller.POSISILOWONGAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_posisilowongan'],
	models: ['m_posisilowongan'],
	stores: ['s_posisilowongan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listposisilowongan',
		selector: 'Listposisilowongan'
	}],


	init: function(){
		this.control({
			'Listposisilowongan': {
				'afterrender': this.posisilowonganAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listposisilowongan button[action=create]': {
				click: this.createRecord
			},
			'Listposisilowongan button[action=delete]': {
				click: this.deleteRecord
			}
		});
	},
	
	posisilowonganAfterRender: function(){
		var posisilowonganStore = this.getListposisilowongan().getStore();
		posisilowonganStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_posisilowongan');
		var r = Ext.ModelManager.create({
			GELLOW		: '',
			TANGGAL		: '',
			KETERANGAN	: ''
		}, model);
		this.getListposisilowongan().getStore().insert(0, r);
		this.getListposisilowongan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListposisilowongan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListposisilowongan().getStore();
		var selection = this.getListposisilowongan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: GELLOW = "'+selection.data.GELLOW+'", IDJAB "'+selection.data.IDJAB+'", dan KODEJAB = "'+selection.data.KODEJAB+'" ?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	}
	
});