Ext.define('YMPI.controller.IMPORTPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_importpres'],
	models: ['m_importpres'],
	stores: ['s_importpres'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listimportpres',
		selector: 'Listimportpres'
	}],


	init: function(){
		this.control({
			'Listimportpres': {
				'afterrender': this.importpresAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listimportpres button[action=filter]': {
				click: this.filterpresensi
			},
			'Listimportpres button[action=shift]': {
				click: this.salahshift
			},
			'Listimportpres button[action=import]': {
				click: this.importpresensi
			},
			'Listimportpres button[action=create]': {
				click: this.createRecord
			},
			'Listimportpres button[action=delete]': {
				click: this.deleteRecord
			},
			'Listimportpres button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listimportpres button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listimportpres button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	importpresAfterRender: function(){
		var getListimportpres = this.getListimportpres();
		var importpresStore = this.getListimportpres().getStore();
		var filter = "Range";
		
		var tglmulai_filter = getListimportpres.down('#tglmulai').getValue();
		var tglsampai_filter = getListimportpres.down('#tglsampai').getValue();
		var tglm = tglmulai_filter.format("yyyy-mm-dd");
		var tgls = tglsampai_filter.format("yyyy-mm-dd");
		importpresStore.proxy.extraParams.tglmulai = tglm;
		importpresStore.proxy.extraParams.tglsampai = tgls;
		
		importpresStore.proxy.extraParams.saring = filter;
		importpresStore.load();
	},
	
	salahshift: function(e){
		console.info(e.text);
		var getListimportpres = this.getListimportpres();
		getListimportpres.down('#ubahshift').setDisabled(false);
		
		var dt = (new Date('07/01/2013 07:15:00') - new Date('07/01/2013 06:14:00'));
		console.info(dt/60000);
		
		var tgl1 = new Date('2013-07-01T14:15:00');
		var tgl2 = new Date('2013-07-01'+'T'+'07:00:00');
		var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
		var t2 = new Date(Ext.Date.format(tgl2,'m/d/Y H:i:s'));
		var rs = (t1 - t2)/60000;
		console.info(tgl1);
		console.info(tgl2);
		console.info(t1);
		console.info(t2);
		console.info(rs);
	},
	
	filterpresensi: function(e){
		console.info(e.text);
		var importpresStore = this.getListimportpres().getStore();
		var filter = null;
		
		if(e.text == "Salah Cek Log")
		{
			importpresStore.proxy.extraParams.saring = e.text;
			importpresStore.load();
			e.setText("Reset");
		}
		else if(e.text == "Reset")
		{
			importpresStore.proxy.extraParams.saring = filter;
			importpresStore.load();
			e.setText("Salah Cek Log");
		}
		
		
	},
	
	importpresensi: function(){
		var getListimportpres = this.getListimportpres();
		//var importpresStore = this.getListimportpres().getStore();
		var tglmulai_filter = getListimportpres.down('#tglmulai').getValue();
		var tglsampai_filter = getListimportpres.down('#tglsampai').getValue();
		
		var tglm = tglmulai_filter.format("yyyy-mm-dd");
		var tgls = tglsampai_filter.format("yyyy-mm-dd");
		//console.info(bulan_filter+" "+tglmulai_filter.format("yyyy-mm-dd")+" "+tglsampai_filter.format("yyyy-mm-dd"));
		console.info(tglm+" "+tgls);
		
		console.info('Fungsi Import Presensi');
		var me = this;
		var msg = function(title, msg){
			Ext.Msg.show({
				title: title,
				msg: msg,
				minWidth: 200,
				modal: true,
				icon: Ext.Msg.INFO,
				buttons: Ext.Msg.OK
			});
		};
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_importpres/ImportPresensi/'+tglm+'/'+tgls,
			timeout: 600000,
			waitMsg: 'Importing Data...',
			success: function(response){
					//var objS = Ext.JSON.decode(response.responseText);
					//console.info(response.responseText);
					//msg('Import Success', 'Data has been imported');
					Ext.Msg.show({
						title: 'Import Success',
						msg: 'Data has been imported',
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.INFO,
						buttons: Ext.Msg.OK,
						fn:function(){
							me.importpresAfterRender();
						}
					});
					//me.importpresAfterRender();
				}
				,
				failure: function(response) {
					console.info(response);
					//msg('Import Failed','Data Fail');
					msg('Import Failed',response.statusText);
					me.importpresAfterRender();
				}
		});
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_importpres');
		var r = Ext.ModelManager.create({
		NIK		: '',TJMASUK		: '',TJKELUAR		: '',ASALDATA		: '',POSTING		: '',USERNAME		: ''}, model);
		this.getListimportpres().getStore().insert(0, r);
		this.getListimportpres().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListimportpres().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListimportpres().getStore();
		var selection = this.getListimportpres().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListimportpres().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_importpres/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListimportpres().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_importpres/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/importpres.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListimportpres().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_importpres/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/importpres.html','importpres_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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