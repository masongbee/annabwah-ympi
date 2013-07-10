Ext.define('YMPI.controller.RIWAYATTRAINING',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_riwayattraining'],
	models: ['m_riwayattraining'],
	stores: ['s_riwayattraining'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listriwayattraining',
		selector: 'Listriwayattraining'
	}, {
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}],


	init: function(){
		this.control({
			'Listriwayattraining': {
				'afterrender': this.riwayattrainingAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listriwayattraining button[action=create]': {
				click: this.createRecord
			},
			'Listriwayattraining button[action=delete]': {
				click: this.deleteRecord
			},
			'Listriwayattraining button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listriwayattraining button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listriwayattraining button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	riwayattrainingAfterRender: function(){
		//var riwayattrainingStore = this.getListriwayattraining().getStore();
		//riwayattrainingStore.load();
	},
	
	createRecord: function(){
		var selection_karyawan = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_riwayattraining');
		var r = Ext.ModelManager.create({
			NIK				: selection_karyawan.data.NIK,
			NOURUT			: '',
			KETERANGAN		: '',
			NAMATRAINING	: '',
			TEMPAT			: '',
			PENYELENGGARA	: '',
			TGLMULAI		: '',
			TGLSAMPAI		: ''
		}, model);
		this.getListriwayattraining().getStore().insert(0, r);
		this.getListriwayattraining().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListriwayattraining().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListriwayattraining().getStore();
		var selection = this.getListriwayattraining().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListriwayattraining().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayattraining/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListriwayattraining().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayattraining/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/riwayattraining.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListriwayattraining().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayattraining/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/riwayattraining.html','riwayattraining_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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