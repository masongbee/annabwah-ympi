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
			'Listimportpres button[action=setmasuk]': {
				click: this.generateMasuk
			},
			'Listimportpres button[action=salah]': {
				click: this.salahshift
			},
			'Listimportpres button[action=ldobel]': {
				click: this.shiftdobel
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
		//console.info(e.text);
		var getListimportpres = this.getListimportpres();
		//console.info(getListimportpres.down('#ubahshift'));
		//getListimportpres.down('#ubahshift').setDisabled(! getListimportpres.down('#ubahshift').disabled);	

		var importpresStore = this.getListimportpres().getStore();
		var filter = null;
		
		if(e.text == "Salah Shift")
		{
			importpresStore.proxy.extraParams.saring = e.text;
			importpresStore.load();
			e.setText("Kembali");
		}
		else if(e.text == "Kembali")
		{
			//importpresStore.proxy.extraParams.saring = filter;
			//importpresStore.load();
			e.setText("Salah Shift");
			this.importpresAfterRender();
		}
	},
	
	shiftdobel: function(e){
		var importpresStore = this.getListimportpres().getStore();
		var filter = null;
		
		if(e.text == "Log Dobel")
		{
			importpresStore.proxy.extraParams.saring = e.text;
			importpresStore.load();
			e.setText("Kembali");
		}
		else if(e.text == "Kembali")
		{
			//importpresStore.proxy.extraParams.saring = filter;
			//importpresStore.load();
			e.setText("Log Dobel");
			this.importpresAfterRender();
		}
	},
	
	generateMasuk: function(e){
		console.info(e.text);
		
	},
	
	importpresensi: function(){
		var btn = this.getListimportpres().down('#btnimport');
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
		var pb = false;
		
		/*var pbar = Ext.create('Ext.ProgressBar', {
		   text:'Initializing...'
		});*/
		
		if(btn.getText() == 'Import')
		{
			btn.setText('Abort');
			pb = true;
			Ext.MessageBox.show({
				title: 'Importing Data',
				progressText: 'Initializing...',
				width:300,
				progress:true,
				closable:false
			});
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_importpres/ImportPresensi/'+tglm+'/'+tgls,
				timeout: 600000,
				success: function(response){
					var objS = Ext.JSON.decode(response.responseText);
					console.info(response.responseText);
					Ext.MessageBox.hide();
					pb=false;
					Ext.Msg.show({
						title: 'Import Success',
						msg: objS.message,
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.INFO,
						buttons: Ext.Msg.OK,
						fn:function(){
							btn.setText('Import');
							me.importpresAfterRender();
						}
					});
				},
				failure: function(response) {
					console.info(response);
					Ext.MessageBox.hide();
					pb=false;
					//msg('Import Failed',response.statusText);
					Ext.Ajax.request({
						url : 'c_importpres/killProsesImport',
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
									btn.setText('Import');
									me.importpresAfterRender();
								}
							});
						}
					});
				}
			});
		}
		else if(btn.getText() == 'Abort')
		{
			btn.setText('Import');
			Ext.Ajax.abortAll();
		}
	
		var task = {
			run: function(){
				if(pb){
					Ext.Ajax.request({
						url : 'c_importpres/getProsesImport',
						timeout: 5000,
						method: 'POST',
						success: function (response, options) {
						   var obj = Ext.decode(response.responseText);
						   //console.info(response);
						   var totalItems = obj.totalData;
						   var totalProcessed = obj.totalProses;

						   // update the progress bar
						   Ext.MessageBox.updateProgress(totalProcessed/totalItems, 'Processed '+totalProcessed+' of '+totalItems);
						}
					});
				}else{
				 runner.stop(task);
				}
			},
			interval: 200 // monitor the progress every 200 milliseconds
		};
		
		// start the TaskRunner
		//pbar.show();
		var runner = new Ext.util.TaskRunner();
		runner.start(task);
		
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
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TANGGAL = "'+selection.data.TANGGAL+' '+selection.data.TJMASUK+'"?', function(btn){
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