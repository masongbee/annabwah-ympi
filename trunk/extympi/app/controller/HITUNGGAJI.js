Ext.define('YMPI.controller.HITUNGGAJI',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_detilgaji'],
	models: ['m_detilgaji'],
	stores: ['s_detilgaji'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listdetilgaji',
		selector: 'Listdetilgaji'
	}],


	init: function(){
		this.control({
			'Listdetilgaji': {
				'afterrender': this.detilgajiAfterRender,
				'selectionchange': this.processAfterSelect
			},
			'Listdetilgaji button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listdetilgaji button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listdetilgaji button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	detilgajiAfterRender: function(){
		//var detilgajiStore = this.getListdetilgaji().getStore();
		//detilgajiStore.load();
	},
	
	processAfterSelect: function(dataview, selections){
		//code
	},
	
	export2Excel: function(){
		var getstore = this.getListdetilgaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_detilgaji/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListdetilgaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_detilgaji/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/detilgaji.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListdetilgaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_detilgaji/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/detilgaji.html','detilgaji_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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