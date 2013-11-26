Ext.define('YMPI.controller.HITUNGGAJI',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_gajibulanan', 'PROSES.v_detilgaji'],
	models: ['m_gajibulanan', 'm_detilgaji'],
	stores: ['s_gajibulanan', 's_detilgaji'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listgajibulanan',
		selector: 'Listgajibulanan'
	}, {
		ref: 'Listdetilgaji',
		selector: 'Listdetilgaji'
	}, {
		ref: 'DetilGajiPanel',
		selector: 'HITUNGGAJI #detilgaji_panel'
	}],


	init: function(){
		this.control({
			'Listgajibulanan': {
				'afterrender': this.gajibulananAfterRender,
				'selectionchange': this.selectRecordGajiBulanan
			},
			'Listgajibulanan button[action=hitunggaji]': {
				click: this.prosesHitungGaji
			},
			'Listgajibulanan button[action=detilgaji]': {
				click: this.detailGajiBy
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
		var getDetilGajiPanel = this.getDetilGajiPanel();
		getDetilGajiPanel.setVisible(false);
		
		var getListgajibulananStore = this.getListgajibulanan().getStore();
		getListgajibulananStore.removeAll();
	},
	
	selectRecordGajiBulanan: function(dataview, selections){
		var getDetilGajiPanel = this.getDetilGajiPanel();
		var getListgajibulanan = this.getListgajibulanan();
		var getListdetilgaji = this.getListdetilgaji();
		
		/*if (selections.length) {
			getDetilGajiPanel.setVisible(true);
			getListdetilgaji.getStore().load({
				params: {
					bulan: selections[0].data.BULAN,
					nik: selections[0].data.NIK
				}
			});
		}else*/
		getDetilGajiPanel.setVisible(false);
		if (selections.length){
			getListgajibulanan.down('#btndetilgaji').setDisabled(!selections.length);
		}else{
			getListgajibulanan.down('#btndetilgaji').setDisabled(!selections.length);
		}
	},
	
	prosesHitungGaji: function(){
		var getListgajibulanan = this.getListgajibulanan();
		var bulan_filter = getListgajibulanan.down('#bulan_filter').getValue();
		var tglmulai_filter = getListgajibulanan.down('#tglmulai').getValue();
		var tglsampai_filter = getListgajibulanan.down('#tglsampai').getValue();
		
		getListgajibulanan.getStore().proxy.extraParams.bulan = bulan_filter;
		getListgajibulanan.getStore().proxy.extraParams.tglmulai = tglmulai_filter;
		getListgajibulanan.getStore().proxy.extraParams.tglsampai = tglsampai_filter;
		getListgajibulanan.getStore().proxy.extraParams.hitunggaji = '';
		//getListgajibulanan.getStore().load();
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_gajibulanan/getAll',
			params: {
				bulan: bulan_filter,
				tglmulai: tglmulai_filter,
				tglsampai: tglsampai_filter,
				hitunggaji: 'hitunggaji'
			},
			success: function(response){
				console.log('success');
				getListgajibulanan.getStore().load();
			},
			timeout: 100000
		});
	},
	
	detailGajiBy: function(){
		var getDetilGajiPanel = this.getDetilGajiPanel();
		var getListgajibulanan = this.getListgajibulanan();
		var getListdetilgaji = this.getListdetilgaji();
		
		var gajibulanan_selections = getListgajibulanan.getSelectionModel().getSelection();
		
		if (gajibulanan_selections.length) {
			getDetilGajiPanel.setVisible(true);
			getListdetilgaji.getStore().load({
				params: {
					bulan: gajibulanan_selections[0].data.BULAN,
					nik: gajibulanan_selections[0].data.NIK
				}
			});
		}
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
		var getListgajibulanan = this.getListgajibulanan();
		var getstore = this.getListgajibulanan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		var bulan_filter = getListgajibulanan.down('#bulan_filter').getValue();
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_gajibulanan/printRecords',
			params: {data: jsonData, bulangaji: bulan_filter},
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