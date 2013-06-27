Ext.define('YMPI.controller.RIWAYATKERJA',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_riwayatkerja'],
	models: ['m_riwayatkerja'],
	stores: ['s_riwayatkerja'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listriwayatkerja',
		selector: 'Listriwayatkerja'
	}, {
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}],


	init: function(){
		this.control({
			'Listriwayatkerja': {
				'afterrender': this.riwayatkerjaAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listriwayatkerja button[action=create]': {
				click: this.createRecord
			},
			'Listriwayatkerja button[action=delete]': {
				click: this.deleteRecord
			},
			'Listriwayatkerja button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listriwayatkerja button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listriwayatkerja button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	riwayatkerjaAfterRender: function(){
		var riwayatkerjaStore = this.getListriwayatkerja().getStore();
		riwayatkerjaStore.load();
	},
	
	createRecord: function(){
		var selection_karyawan = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_riwayatkerja');
		var r = Ext.ModelManager.create({
			NIK				: selection_karyawan.data.NIK,
			NOURUT			: '',
			TAHUN			: '',
			POSISI			: '',
			NAMAPERUSH		: '',
			ALAMAT			: '',
			LAMABEKERJA		: '',
			ALASANBERHENTI	: ''
		}, model);
		this.getListriwayatkerja().getStore().insert(0, r);
		this.getListriwayatkerja().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListriwayatkerja().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListriwayatkerja().getStore();
		var selection = this.getListriwayatkerja().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListriwayatkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatkerja/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListriwayatkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatkerja/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/riwayatkerja.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListriwayatkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatkerja/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/riwayatkerja.html','riwayatkerja_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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