Ext.define('YMPI.controller.PENUGASANKAR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_penugasankar'],
	models: ['m_penugasankar'],
	stores: ['s_penugasankar','s_karyawan','s_karyawan_byunitkerja'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpenugasankar',
		selector: 'Listpenugasankar'
	}],


	init: function(){
		this.control({
			'Listpenugasankar': {
				'afterrender': this.penugasankarAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpenugasankar button[action=create]': {
				click: this.createRecord
			},
			'Listpenugasankar button[action=delete]': {
				click: this.deleteRecord
			}
		});
	},
	
	penugasankarAfterRender: function(){
		var penugasankarStore = this.getListpenugasankar().getStore();
		var karyawanStore = this.getStore('s_karyawan');
		penugasankarStore.load();
		karyawanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_penugasankar');
		var r = Ext.ModelManager.create({
			NOTUGAS 		: ''
			,NIK 			: ''
			,TGLMULAI 		: ''
			,TGLSAMPAI 		: ''
			,LAMA 			: ''
			,KOTA 			: ''
			,RINCIANTUGAS 	: ''
			,KETERANGAN 	: ''
			,NIKATASAN1 	: user_nik
			,NIKPERSONALIA 	: nik_hrd
		}, model);
		this.getListpenugasankar().getStore().insert(0, r);
		this.getListpenugasankar().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpenugasankar().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpenugasankar().getStore();
		var selection = this.getListpenugasankar().getSelectionModel().getSelection()[0];
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