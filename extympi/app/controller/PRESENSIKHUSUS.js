Ext.define('YMPI.controller.PRESENSIKHUSUS',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_presensikhusus'],
	models: ['m_presensikhusus'],
	stores: ['s_presensikhusus'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpresensikhusus',
		selector: 'Listpresensikhusus'
	}],


	init: function(){
		this.control({
			'Listpresensikhusus': {
				'afterrender': this.presensikhususAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpresensikhusus button[action=create]': {
				click: this.createRecord
			},
			'Listpresensikhusus button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpresensikhusus button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpresensikhusus button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpresensikhusus button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	presensikhususAfterRender: function(){
		var presensikhususStore = this.getListpresensikhusus().getStore();
		presensikhususStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_presensikhusus');
		var r = Ext.ModelManager.create({
		ID		: '',NIK		: '',NAMASHIFT		: '',SHIFTKE		: '',TANGGAL		: '',TJMASUK		: '',TJKELUAR		: '',ASALDATA		: '',JENISABSEN		: '',JENISLEMBUR		: '',EXTRADAY		: ''}, model);
		this.getListpresensikhusus().getStore().insert(0, r);
		this.getListpresensikhusus().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpresensikhusus().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpresensikhusus().getStore();
		var selection = this.getListpresensikhusus().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListpresensikhusus().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensikhusus/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpresensikhusus().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensikhusus/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/presensikhusus.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpresensikhusus().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensikhusus/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/presensikhusus.html','presensikhusus_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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