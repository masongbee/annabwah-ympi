Ext.define('YMPI.controller.EDITPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_editpres'],
	models: ['m_editpres'],
	stores: ['s_editpres'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listeditpres',
		selector: 'Listeditpres'
	}],


	init: function(){
		this.control({
			'Listeditpres': {
				'afterrender': this.editpresAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listeditpres button[action=create]': {
				click: this.createRecord
			},
			'Listeditpres button[action=delete]': {
				click: this.deleteRecord
			},
			'Listeditpres button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listeditpres button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listeditpres button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	editpresAfterRender: function(){
		var editpresStore = this.getListeditpres().getStore();
		editpresStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_editpres');
		var r = Ext.ModelManager.create({
		NIK		: '',TJMASUK		: '',TJKELUAR		: '',ASALDATA		: '',POSTING		: '',USERNAME		: ''}, model);
		this.getListeditpres().getStore().insert(0, r);
		this.getListeditpres().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListeditpres().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListeditpres().getStore();
		var selection = this.getListeditpres().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListeditpres().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_editpres/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListeditpres().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_editpres/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/editpres.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListeditpres().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_editpres/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/editpres.html','editpres_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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