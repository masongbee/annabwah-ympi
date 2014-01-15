Ext.define('YMPI.controller.IMPORTPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.v_importpres'],
	models: ['m_importpres'],
	stores: ['s_importpres','YMPI.store.s_karyawan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listimportpres',
		selector: 'Listimportpres'
	}],


	init: function(){
		this.control({
			'Listimportpres': {
				'afterrender': this.importpresAfterRender,
				'selectionchange': this.enableDelete,
				'beforeedit': this.beforeeditGrid
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
			// ------------------------------ Proses Eko -----------------------------
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
					Ext.MessageBox.hide();
					pb=false;
					Ext.Msg.show({
						title: 'Import Success',
						msg: 'Import Berhasil...',
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
				,
				failure: function(response) {
					console.info(response);
					Ext.MessageBox.hide();
					pb=false;
					//msg('Import Failed',response.statusText);
					Ext.Ajax.request({
						url : 'c_importpres/killProsesImport',
						timeout: 600000,
						method: 'POST',
						success: function (response, options) {
						   //var obj = Ext.JSON.decode(response.responseText);
							Ext.Msg.show({
								title: 'Data dibatalkan...',
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
			// -----------------------------------------------------------------------
			/*var nextsampai = new Date();
			nextsampai.setDate(tglsampai_filter.getDate() + 1);
			console.log(dt);
			console.log(parseInt((nextsampai.getTime() - tglmulai_filter.getTime())/(24*3600*1000)));*/
			var selisih_tglfilter = parseInt((tglsampai_filter.getTime() - tglmulai_filter.getTime())/(24*3600*1000));
			/*var list = [];
			for (var i=0; i<=selisih_tglfilter; i++) {
				list.push(i);
			}
			
			var x = 0;
			
			var customAlert = function(loopi,callback) {
				// fancy code to show your message
				var tglstart = new Date();
				tglstart.setDate(tglmulai_filter.getDate() + loopi);
				var tglend = new Date();
				tglend.setDate(tglsampai_filter.getDate() + (loopi+1));
				
				var tglm = tglstart.format("yyyy-mm-dd");
				var tgls = tglend.format("yyyy-mm-dd");
				Ext.Ajax.request({
					method: 'POST',
					url: 'c_importpres/ImportPresensi/'+tglm+'/'+tgls,
					timeout: 1000000,
					success: function(response){
						var obj = Ext.JSON.decode(response.responseText);
						//console.info(response.responseText);
						if (obj.success) {
							if (loopi == selisih_tglfilter) {
								Ext.Msg.show({
									title: 'Import Success',
									msg: 'Import Success',
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
							callback();
						}
					},
					failure: function(response) {
						//console.info(response);
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
				// do callback when ready
				//callback();
			};
			
			var loopArray = function(arr) {
				// call itself
				customAlert(arr[x],function(){
					// set x to next item
					x++;
					// any more items in array?
					if(x < arr.length) {
						loopArray(arr);   
					}
					
				}); 
			};
			
			// start 'loop'
			loopArray(list);*/
			
			
			//mas muk proc
			/*var arrlist = [];
			for (var i=0; i<selisih_tglfilter; i++) {
				arrlist.push(i);
			}
			var x = 0;
			
			var customAlert = function(loopi,callback) {
				btn.setText('Abort');
				pb = true;
				Ext.MessageBox.show({
					title: 'Importing Data',
					progressText: 'Initializing...',
					width:300,
					progress:true,
					closable:false
				});
				
				var tglstart = new Date(tglmulai_filter);
				tglstart.setDate(tglmulai_filter.getDate() + loopi);
				var tglend = new Date(tglmulai_filter);
				tglend.setDate(tglmulai_filter.getDate() + (loopi+1));
				
				var tglm = tglstart.format("yyyy-mm-dd");
				var tgls = tglend.format("yyyy-mm-dd");
				Ext.Ajax.request({
					method: 'POST',
					url: 'c_importpres/ImportPresensi/'+tglm+'/'+tgls,
					timeout: 1000000,
					success: function(response){
						var obj = Ext.JSON.decode(response.responseText);
						if (obj.success) {
							console.log(loopi);
							
							if (loopi == (selisih_tglfilter - 1)) {
								Ext.MessageBox.hide();
								pb=false;
								Ext.Msg.show({
									title: 'Import Success',
									msg: 'Import Success',
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
							
							callback();
						}
					},
					failure: function(response) {
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
				
			};
			
			var loopArray = function(arr) {
				// call itself
				customAlert(arr[x],function(){
					// set x to next item
					x++;
					// any more items in array?
					if(x < arr.length) {
						loopArray(arr);   
					}
				}); 
			};
			
			// start 'loop'
			loopArray(arrlist);*/
			
			//-------------------------------------------------
			/*for (var i=0; i<=selisih_tglfilter;) {
				console.log(i);
				var tglstart = new Date();
				tglstart.setDate(tglmulai_filter.getDate() + i);
				var tglend = new Date();
				tglend.setDate(tglsampai_filter.getDate() + (i+1));
				
				var tglm = tglstart.format("yyyy-mm-dd");
				var tgls = tglend.format("yyyy-mm-dd");
				Ext.Ajax.request({
					method: 'POST',
					url: 'c_importpres/ImportPresensi/'+tglm+'/'+tgls,
					timeout: 1000000,
					success: function(response){
						var obj = Ext.JSON.decode(response.responseText);
						//console.info(response.responseText);
						i++;
						if (obj.success) {
							if (i == selisih_tglfilter) {
								Ext.Msg.show({
									title: 'Import Success',
									msg: 'Import Success',
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
						}
					},
					failure: function(response) {
						//console.info(response);
						//msg('Import Failed',response.statusText);
						i = selisih_tglfilter;
						
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
			}*/
			/*btn.setText('Abort');
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
				timeout: 1000000,
				success: function(response){
					//var objS = Ext.JSON.decode(response.responseText);
					//console.info(response.responseText);
					Ext.MessageBox.hide();
					pb=false;
					Ext.Msg.show({
						title: 'Import Success',
						msg: 'Import Success',
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
					//console.info(response);
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
			});*/
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
						timeout: 600000,
						method: 'POST',
						success: function (response, options) {
							var obj = Ext.JSON.decode(response.responseText);
							//console.info(response);
							var totalItems = obj.totalData;
							var totalProcessed = obj.totalProses;

							// update the progress bar
							if(!(totalProcessed == 0 && totalItems == 0) && (totalProcessed == totalItems))
							{
								Ext.MessageBox.updateProgress(0,'Preparing Data... Please Wait...');
							}
							else if(!(totalProcessed == 0 && totalItems == 0))
							{
								Ext.MessageBox.updateProgress(totalProcessed/totalItems, 'Processed '+totalProcessed+' of '+totalItems);
							}
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
	
	beforeeditGrid: function(editor, e){
		var karyawanStore = this.getStore('YMPI.store.s_karyawan');
		karyawanStore.getProxy().extraParams.query = e.record.data.NIK;
		karyawanStore.load();
	},
	
	deleteRecord: function(){
		var getstore = this.getListimportpres().getStore();
		var selections = this.getListimportpres().getSelectionModel().getSelection();
		if(selections){
			Ext.Msg.confirm('Confirmation', 'Apakah Anda yakin menghapus data terpilih?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selections);
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