Ext.define('YMPI.controller.DETILSHIFT',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_detilshift'],
	models: ['m_detilshift'],
	stores: ['s_detilshift'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listdetilshift',
		selector: 'Listdetilshift'
	},{
		ref: 'Listshift',
		selector: 'Listshift'
	}],


	init: function(){
		this.control({
			'Listdetilshift': {
				'afterrender': this.detilshiftAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listdetilshift button[action=create]': {
				click: this.createRecord
			},
			'Listdetilshift button[action=delete]': {
				click: this.deleteRecord
			},
			'Listdetilshift button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listdetilshift button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listdetilshift button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	detilshiftAfterRender: function(){
		//var detilshiftStore = this.getListdetilshift().getStore();
		//detilshiftStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_detilshift');
		var r = Ext.ModelManager.create({
		NAMASHIFT		: '',SHIFTKE		: '',KETERANGAN		: '',POLASHIFT		: ''}, model);
		this.getListdetilshift().getStore().insert(0, r);
		this.getListdetilshift().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListdetilshift().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListdetilshift().getStore();
		var selection = this.getListdetilshift().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: SHIFTKE = "'+selection.data.SHIFTKE+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListdetilshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_detilshift/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListdetilshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_detilshift/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/detilshift.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListdetilshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_detilshift/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/detilshift.html','detilshift_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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