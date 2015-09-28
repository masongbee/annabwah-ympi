Ext.define('YMPI.controller.PELAMAR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_pelamar'],
	models: ['m_pelamar'],
	stores: ['s_pelamar'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpelamar',
		selector: 'Listpelamar'
	}],


	init: function(){
		this.control({
			'Listpelamar': {
				'afterrender': this.pelamarAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpelamar button[action=create]': {
				click: this.createRecord
			},
			'Listpelamar button[action=delete]': {
				click: this.deleteRecord
			}
		});
	},
	
	pelamarAfterRender: function(){
		var pelamarStore = this.getListpelamar().getStore();
		pelamarStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_pelamar');
		var r = Ext.ModelManager.create({
			KTP				: '',
			NAMAPELAMAR		: '',
			AGAMA			: '',
			ALAMAT			: '',
			JENISKEL		: '',
			JURUSAN			: '',
			KAWIN			: '',
			KOTA			: '',
			NAMASEKOLAH		: '',
			PENDIDIKAN		: '',
			TELEPON			: '',
			TGLLAHIR		: '',
			TMPLAHIR		: '',
			STATUSPELAMAR	: 'A',
			GELLOW			: '',
			KODEJAB			: '',
			IDJAB			: ''
		}, model);
		this.getListpelamar().getStore().insert(0, r);
		this.getListpelamar().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpelamar().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpelamar().getStore();
		var selection = this.getListpelamar().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KTP = "'+selection.data.KTP+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	}
	
});