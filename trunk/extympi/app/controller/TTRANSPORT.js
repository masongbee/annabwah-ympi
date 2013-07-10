Ext.define('YMPI.controller.TTRANSPORT',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_ttransport'],
	models: ['m_ttransport'],
	stores: ['s_ttransport'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listttransport',
		selector: 'Listttransport'
	}],


	init: function(){
		this.control({
			'Listttransport': {
				'afterrender': this.ttransportAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listttransport button[action=create]': {
				click: this.createRecord
			},
			'Listttransport button[action=delete]': {
				click: this.deleteRecord
			},
			'Listttransport button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listttransport button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listttransport button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	ttransportAfterRender: function(){
		var ttransportStore = this.getListttransport().getStore();
		ttransportStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_ttransport');
		var r = Ext.ModelManager.create({
		VALIDFROM		: '',NOURUT		: '',GRADE		: '',KODEJAB		: '',NIK		: '',ZONA		: '',RPTTRANSPORT		: '',USERNAME		: ''}, model);
		this.getListttransport().getStore().insert(0, r);
		this.getListttransport().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListttransport().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListttransport().getStore();
		var selection = this.getListttransport().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListttransport().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_ttransport/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListttransport().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_ttransport/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/ttransport.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListttransport().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_ttransport/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/ttransport.html','ttransport_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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