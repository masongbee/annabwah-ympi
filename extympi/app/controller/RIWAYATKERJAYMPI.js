Ext.define('YMPI.controller.RIWAYATKERJAYMPI',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_riwayatkerjaympi'],
	models: ['m_riwayatkerjaympi'],
	stores: ['s_riwayatkerjaympi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listriwayatkerjaympi',
		selector: 'Listriwayatkerjaympi'
	}, {
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}],


	init: function(){
		this.control({
			'Listriwayatkerjaympi': {
				'afterrender': this.riwayatkerjaympiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listriwayatkerjaympi button[action=create]': {
				click: this.createRecord
			},
			'Listriwayatkerjaympi button[action=delete]': {
				click: this.deleteRecord
			},
			'Listriwayatkerjaympi button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listriwayatkerjaympi button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listriwayatkerjaympi button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	riwayatkerjaympiAfterRender: function(){
		var riwayatkerjaympiStore = this.getListriwayatkerjaympi().getStore();
		riwayatkerjaympiStore.load();
	},
	
	createRecord: function(){
		var selection_karyawan = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_riwayatkerjaympi');
		var r = Ext.ModelManager.create({
			NIK			: selection_karyawan.data.NIK,
			NOURUT		: '',
			NAMAUNIT	: '',
			TGLMULAI	: '',
			TGLSAMPAI	: ''
		}, model);
		this.getListriwayatkerjaympi().getStore().insert(0, r);
		this.getListriwayatkerjaympi().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListriwayatkerjaympi().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListriwayatkerjaympi().getStore();
		var selection = this.getListriwayatkerjaympi().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListriwayatkerjaympi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatkerjaympi/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListriwayatkerjaympi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatkerjaympi/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/riwayatkerjaympi.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListriwayatkerjaympi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatkerjaympi/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/riwayatkerjaympi.html','riwayatkerjaympi_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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