Ext.define('YMPI.controller.PRESENSILEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_presensilembur'],
	models: ['m_presensilembur'],
	stores: ['s_presensilembur'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpresensilembur',
		selector: 'Listpresensilembur'
	}],


	init: function(){
		this.control({
			'Listpresensilembur': {
				'afterrender': this.presensilemburAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpresensilembur button[action=create]': {
				click: this.createRecord
			},
			'Listpresensilembur button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpresensilembur button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpresensilembur button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpresensilembur button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	presensilemburAfterRender: function(){
		var presensilemburStore = this.getListpresensilembur().getStore();
		presensilemburStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_presensilembur');
		var r = Ext.ModelManager.create({
		NIK		: '',TJMASUK		: Ext.Date.format(Ext.Date.now(),'Y-m-d H:i:s'),NOLEMBUR		: '',NOURUT		: '',JENISLEMBUR		: ''}, model);
		this.getListpresensilembur().getStore().insert(0, r);
		this.getListpresensilembur().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpresensilembur().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpresensilembur().getStore();
		var selection = this.getListpresensilembur().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListpresensilembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensilembur/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpresensilembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensilembur/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/presensilembur.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpresensilembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensilembur/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/presensilembur.html','presensilembur_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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