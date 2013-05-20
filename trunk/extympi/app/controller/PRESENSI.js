Ext.define('YMPI.controller.PRESENSI',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_presensi'],
	models: ['m_presensi'],
	stores: ['s_presensi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpresensi',
		selector: 'Listpresensi'
	}],


	init: function(){
		this.control({
			'Listpresensi': {
				'afterrender': this.presensiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpresensi button[action=create]': {
				click: this.createRecord
			},
			'Listpresensi button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpresensi button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpresensi button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpresensi button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	presensiAfterRender: function(){
		var presensiStore = this.getListpresensi().getStore();
		presensiStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_presensi');
		var r = Ext.ModelManager.create({
		NIK		: '',TJMASUK		: '',TJKELUAR		: '',ASALDATA		: '',POSTING		: '',USERNAME		: ''}, model);
		this.getListpresensi().getStore().insert(0, r);
		this.getListpresensi().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpresensi().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpresensi().getStore();
		var selection = this.getListpresensi().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TJMASUK = "'+selection.data.TJMASUK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensi/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensi/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/presensi.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensi/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/presensi.html','presensi_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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