Ext.define('YMPI.controller.KELUARGA',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_keluarga'],
	models: ['m_keluarga'],
	stores: ['s_keluarga'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listkeluarga',
		selector: 'Listkeluarga'
	}, {
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}],


	init: function(){
		this.control({
			'Listkeluarga': {
				'afterrender': this.keluargaAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listkeluarga button[action=create]': {
				click: this.createRecord
			},
			'Listkeluarga button[action=delete]': {
				click: this.deleteRecord
			},
			'Listkeluarga button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listkeluarga button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listkeluarga button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	keluargaAfterRender: function(){
		//var keluargaStore = this.getListkeluarga().getStore();
		//keluargaStore.load();
	},
	
	createRecord: function(){
		var selection_karyawan = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_keluarga');
		var r = Ext.ModelManager.create({
			NOURUT		: '',
			STATUSKEL	: '',
			NIK			: selection_karyawan.data.NIK,
			NAMAKEL		: '',
			JENISKEL	: '',
			ALAMAT		: '',
			TMPLAHIR	: '',
			TGLLAHIR	: '',
			PENDIDIKAN	: '',
			PEKERJAAN	: '',
			TANGGUNGSPKK: '',
			TGLMENINGGAL: ''
		}, model);
		this.getListkeluarga().getStore().insert(0, r);
		this.getListkeluarga().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListkeluarga().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListkeluarga().getStore();
		var selection = this.getListkeluarga().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NIK = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListkeluarga().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_keluarga/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListkeluarga().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_keluarga/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/keluarga.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListkeluarga().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_keluarga/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/keluarga.html','keluarga_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	}
	
});