Ext.define('YMPI.controller.KELOMPOK',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_kelompok'],
	models: ['m_kelompok'],
	stores: ['s_kelompok'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listkelompok',
		selector: 'Listkelompok'
	}],


	init: function(){
		this.control({
			'Listkelompok': {
				'afterrender': this.kelompokAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listkelompok button[action=create]': {
				click: this.createRecord
			},
			'Listkelompok button[action=delete]': {
				click: this.deleteRecord
			},
			'Listkelompok button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listkelompok button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listkelompok button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	kelompokAfterRender: function(){
		var kelompokStore = this.getListkelompok().getStore();
		kelompokStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_kelompok');
		var r = Ext.ModelManager.create({
		KODEKEL		: '',NAMAKEL		: ''}, model);
		this.getListkelompok().getStore().insert(0, r);
		this.getListkelompok().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListkelompok().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListkelompok().getStore();
		var selection = this.getListkelompok().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODEKEL = "'+selection.data.KODEKEL+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListkelompok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kelompok/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListkelompok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kelompok/export2PDF',
			params: {data: jsonData},
			success: function(response){
				// window.open('./temp/the_pdf_output.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListkelompok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kelompok/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/kelompok.html','kelompok_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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