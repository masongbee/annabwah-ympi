Ext.define('YMPI.controller.HITPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_hitungpresensi'],
	models: ['m_hitungpresensi'],
	stores: ['s_hitungpresensi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listhitungpresensi',
		selector: 'Listhitungpresensi'
	}],


	init: function(){
		this.control({
			'Listhitungpresensi': {
				'afterrender': this.hitungpresensiAfterRender
			},
			'Listhitungpresensi button[action=hitungpresensi]': {
				click: this.prosesHitungPresensi
			},
			'Listhitungpresensi button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listhitungpresensi button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listhitungpresensi button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	hitungpresensiAfterRender: function(){
		var hitungpresensiStore = this.getListhitungpresensi().getStore();
		hitungpresensiStore.load();
	},
	
	prosesHitungPresensi: function(){
		var getListhitungpresensi = this.getListhitungpresensi();
		var bulan_filter = getListhitungpresensi.down('#bulan_filter').getValue();
		var tglmulai_filter = getListhitungpresensi.down('#tglmulai').getValue();
		var tglsampai_filter = getListhitungpresensi.down('#tglsampai').getValue();
		
		var tglm = tglmulai_filter.format("yyyy-mm-dd");
		var tgls = tglsampai_filter.format("yyyy-mm-dd");
		//console.info(bulan_filter+" "+tglmulai_filter.format("yyyy-mm-dd")+" "+tglsampai_filter.format("yyyy-mm-dd"));
		console.info(bulan_filter+" "+tglm+" "+tgls);
		
		var me = this;
		var msg = function(title, msg) {
			Ext.Msg.show({
				title: title,
				msg: msg,
				minWidth: 200,
				modal: true,
				progress: true,
				progressText: 'Please Wait ...',
				icon: Ext.Msg.INFO,
				buttons: Ext.Msg.OK
			});
		};
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_hitungpresensi/LoopUpdate/'+bulan_filter+'/'+tglm+'/'+tgls,
			waitMsg: 'Hitung Presensi...',
			success: function(response){
				msg('Success', 'Data Telah Diproses...');
				//msg('Login Success', action.response.responseText);
				me.hitungpresensiAfterRender();
			},
			failure: function(response) {
				msg('Failed','Data Gagal Diproses...');
				//msg('Login Failed', action.response.responseText);
			}
		});
	},
	
	export2Excel: function(){
		var getstore = this.getListhitungpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_hitungpresensi/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListhitungpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_hitungpresensi/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/hitungpresensi.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListhitungpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_hitungpresensi/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/hitungpresensi.html','hitungpresensi_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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