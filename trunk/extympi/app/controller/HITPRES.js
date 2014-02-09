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
		var getListhitungpresensi = this.getListhitungpresensi();
		var hitungpresensiStore = this.getListhitungpresensi().getStore();
		
		/*var filter = "Range";		
		var tglmulai_filter = getListhitungpresensi.down('#tglmulai').getValue();
		var tglsampai_filter = getListhitungpresensi.down('#tglsampai').getValue();
		var tglm = tglmulai_filter.format("yyyy-mm-dd");
		var tgls = tglsampai_filter.format("yyyy-mm-dd");
		hitungpresensiStore.proxy.extraParams.tglmulai = tglm;
		hitungpresensiStore.proxy.extraParams.tglsampai = tgls;
		
		hitungpresensiStore.proxy.extraParams.saring = filter;
		hitungpresensiStore.load();*/
	},
	
	prosesHitungPresensi: function(){
		var getListhitungpresensi = this.getListhitungpresensi();
		var btn = getListhitungpresensi.down('#btnHitung');
		var bulan_filter = getListhitungpresensi.down('#bulan_filter').getValue();
		var tglmulai_filter = getListhitungpresensi.down('#tglmulai').getValue();
		var tglsampai_filter = getListhitungpresensi.down('#tglsampai').getValue();
		
		var tglm = tglmulai_filter.format("yyyy-mm-dd");
		var tgls = tglsampai_filter.format("yyyy-mm-dd");
		//console.info(bulan_filter+" "+tglmulai_filter.format("yyyy-mm-dd")+" "+tglsampai_filter.format("yyyy-mm-dd"));
		console.info(bulan_filter+" "+tglm+" "+tgls);
		
		var me = this;
		var pb = false;
		
		/*var pbar = Ext.create('Ext.ProgressBar', {
		   text:'Initializing...'
		});*/
		
		if(btn.getText() == 'Hitung Presensi')
		{
			btn.setText('Abort');
			pb = true;
			Ext.MessageBox.show({
				title: 'Hitung Presensi',
				progressText: 'Initializing...',
				width:300,
				progress:true,
				closable:false
			});
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_hitungpresensi/LoopUpdate/'+bulan_filter+'/'+tglm+'/'+tgls,
				timeout: 600000,
				success: function(response){
					var objS = Ext.JSON.decode(response.responseText);
					console.info(response.responseText);
					Ext.MessageBox.hide();
					pb=false;
					Ext.Msg.show({
						title: 'Hitung Presensi',
						msg: objS.message,
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.INFO,
						buttons: Ext.Msg.OK,
						fn:function(){
							btn.setText('Hitung Presensi');
							me.hitungpresensiAfterRender();
						}
					});
					getListhitungpresensi.getStore().reload();
				},
				failure: function(response) {
					Ext.MessageBox.hide();
					pb=false;
					//msg('Import Failed',response.statusText);
					Ext.Ajax.request({
						url : 'c_hitungpresensi/killProsesHitpres',
						timeout: 5000,
						method: 'POST',
						success: function (response, options) {
						   //var obj = Ext.JSON.decode(response.responseText);
							Ext.Msg.show({
								title: 'Data Aborted...',
								msg: response.statusText,
								minWidth: 200,
								modal: true,
								icon: Ext.Msg.INFO,
								buttons: Ext.Msg.OK,
								fn:function(){
									btn.setText('Hitung Presensi');
									me.hitungpresensiAfterRender();
								}
							});
						}
					});
				}
			});
		}
		else if(btn.getText() == 'Abort')
		{
			btn.setText('Hitung Presensi');
			Ext.Ajax.abortAll();
		}
	
		if(pb){
			Ext.MessageBox.wait('Please Wait...','Hitung Presensi');
		}
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