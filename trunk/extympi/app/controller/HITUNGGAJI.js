Ext.define('YMPI.controller.HITUNGGAJI',{
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
				'afterrender': this.gajibulananAfterRender,
				'selectionchange': this.processAfterSelect
			},
			'Listgajibulanan button[action=hitunggaji]': {
				click: this.prosesHitunggaji
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
	
	gajibulananAfterRender: function(){
		//var gajibulananStore = this.getListgajibulanan().getStore();
		//gajibulananStore.load();
	},
	
	processAfterSelect: function(dataview, selections){
		//code
	},
	
	prosesHitunggaji: function(){
		var getListgajibulanan = this.getListgajibulanan();
		var bulan_filter = getListgajibulanan.down('#bulan_filter').getValue();
		var tglmulai_filter = getListgajibulanan.down('#tglmulai').getValue();
		var tglsampai_filter = getListgajibulanan.down('#tglsampai').getValue();
		
		getListgajibulanan.getStore().load({
			params: {
				bulan: bulan_filter,
				tglmulai: tglmulai_filter,
				tglsampai: tglsampai_filter
			}
		});
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