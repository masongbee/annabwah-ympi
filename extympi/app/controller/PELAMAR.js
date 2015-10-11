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
				'selectionchange': this.enableDelete,
				'beforeselect': this.beforeselectGrid,
				'beforeedit': this.beforeeditGrid
			},
			'Listpelamar button[action=create]': {
				click: this.createRecord
			},
			'Listpelamar button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpelamar button[action=mutasi]': {
				click: this.mutasiRecord
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
		if (selections.length > 0) {
			var data = selections[0].data;
			if (data.STATUSPELAMAR == 'F') {
				this.getListpelamar().down('#btndelete').setDisabled(true);
				this.getListpelamar().down('#btnmutasi').setDisabled(false);
			} else {
				this.getListpelamar().down('#btnmutasi').setDisabled(true);

				if (data.STATUSPELAMAR == 'A') {
					this.getListpelamar().down('#btndelete').setDisabled(false);
				} else{
					this.getListpelamar().down('#btndelete').setDisabled(true);
				};
			};
		} else {
			this.getListpelamar().down('#btndelete').setDisabled(!selections.length);
			this.getListpelamar().down('#btnmutasi').setDisabled(!selections.length);
		}
		
	},

	beforeselectGrid: function(thisme, record, index){
		if (record.data.STATUSPELAMAR == 'F') {
			return true;
		} else {
			return false;
		};
	},

	beforeeditGrid: function(editor, e){
		var statuspelamar = e.record.get('STATUSPELAMAR');
		
		if (statuspelamar == 'A') {
			return true;
		}
		return false;
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
	},

	mutasiRecord: function(dataview, selections) {
		var getstore = this.getListpelamar().getStore();
		var selections = this.getListpelamar().getSelectionModel().getSelection();
		var jsonData = Ext.encode(Ext.pluck(selections, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pelamar/mutasiPelamar',
			params: {data: jsonData},
			success: function(response){
				getstore.load();
				var objResponse = Ext.JSON.decode(response.responseText);
				Ext.MessageBox.show({
					title: 'Info',
					msg: objResponse.message,
					buttons: Ext.MessageBox.OK,
					icon: Ext.Msg.INFO
				});
			}
		});
	}
	
});