Ext.define('YMPI.controller.MONKAR',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_monkar'],
	models: ['m_karyawan'],
	stores: ['s_karyawan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listmonkar',
		selector: 'Listmonkar'
	}],


	init: function(){
		this.control({
			'Listmonkar': {
				'afterrender': this.monkarAfterRender
			},
			'Listmonkar button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listmonkar button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listmonkar button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	monkarAfterRender: function(){
		var monkarStore = this.getListmonkar().getStore();
		//monkarStore.load();
		/*monkarStore.load({
			params:{
				query: '--'
			}
		});*/
	},
	
	export2Excel: function(){
		var getstore = this.getListmonkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkacamata/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListmonkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkacamata/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tkacamata.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListmonkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkacamata/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tkacamata.html','tkacamata_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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