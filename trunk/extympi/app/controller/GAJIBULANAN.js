Ext.define('YMPI.controller.GAJIBULANAN',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_gajibulanan'],
	models: ['m_gajibulanan'],
	stores: ['s_gajibulanan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listgajibulanan',
		selector: 'Listgajibulanan'
	}],


	init: function(){
		this.control({
			'Listgajibulanan': {
				'selectionchange': this.enableDelete
			},
			'Listgajibulanan button[action=create]': {
				click: this.createRecord
			},
			'Listgajibulanan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listgajibulanan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listgajibulanan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	enableDelete: function(dataview, selections){
		//code 
	},
	
	export2Excel: function(){
		var getstore = this.getListgajibulanan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_gajibulanan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListgajibulanan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_gajibulanan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/gajibulanan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListgajibulanan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_gajibulanan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/gajibulanan.html','gajibulanan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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