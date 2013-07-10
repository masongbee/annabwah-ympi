Ext.define('YMPI.controller.PENGHARGAAN',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_penghargaan'],
	models: ['m_penghargaan'],
	stores: ['s_penghargaan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpenghargaan',
		selector: 'Listpenghargaan'
	}, {
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}],


	init: function(){
		this.control({
			'Listpenghargaan': {
				'afterrender': this.penghargaanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpenghargaan button[action=create]': {
				click: this.createRecord
			},
			'Listpenghargaan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpenghargaan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpenghargaan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpenghargaan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	penghargaanAfterRender: function(){
		var penghargaanStore = this.getListpenghargaan().getStore();
		penghargaanStore.load();
	},
	
	createRecord: function(){
		var selection_karyawan = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_penghargaan');
		var r = Ext.ModelManager.create({
			NIK			: selection_karyawan.data.NIK,
			NOURUT		: '',
			PENGHARGAAN	: '',
			BULAN		: '',
			TAHUN		: ''
		}, model);
		this.getListpenghargaan().getStore().insert(0, r);
		this.getListpenghargaan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpenghargaan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpenghargaan().getStore();
		var selection = this.getListpenghargaan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NOURUT = "'+selection.data.NOURUT+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListpenghargaan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_penghargaan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpenghargaan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_penghargaan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/penghargaan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpenghargaan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_penghargaan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/penghargaan.html','penghargaan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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