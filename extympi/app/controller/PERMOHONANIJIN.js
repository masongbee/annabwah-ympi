Ext.define('YMPI.controller.PERMOHONANIJIN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_permohonanijin','TRANSAKSI.v_permohonanijin_form'],
	models: ['m_permohonanijin'],
	stores: ['s_permohonanijin'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpermohonanijin',
		selector: 'Listpermohonanijin'
	}, {
		ref: 'v_permohonanijin_form',
		selector: 'v_permohonanijin_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_permohonanijin_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_permohonanijin_form #create'
	}, {
		ref: 'PERMOHONANIJIN',
		selector: 'PERMOHONANIJIN'
	}],


	init: function(){
		this.control({
			'PERMOHONANIJIN': {
				'afterrender': this.permohonanijinAfterRender
			},
			'Listpermohonanijin': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListpermohonanijin
			},
			'Listpermohonanijin button[action=create]': {
				click: this.createRecord
			},
			'Listpermohonanijin button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpermohonanijin button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpermohonanijin button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpermohonanijin button[action=print]': {
				click: this.printRecords
			},
			'v_permohonanijin_form button[action=save]': {
				click: this.saveV_permohonanijin_form
			},
			'v_permohonanijin_form button[action=create]': {
				click: this.saveV_permohonanijin_form
			},
			'v_permohonanijin_form button[action=cancel]': {
				click: this.cancelV_permohonanijin_form
			}
		});
	},
	
	permohonanijinAfterRender: function(){
		var permohonanijinStore = this.getListpermohonanijin().getStore();
		
		permohonanijinStore.proxy.extraParams.nik = user_nik;
		permohonanijinStore.load();
	},
	
	createRecord: function(){
		var thisme = this;
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_permohonanijin');
		var r = Ext.ModelManager.create({
			NOIJIN			: '',
			NIK				: '',
			JENISABSEN		: '',
			TANGGAL			: '',
			JAMDARI			: '',
			JAMSAMPAI		: '',
			KEMBALI			: '',
			AMBILCUTI		: '',
			SISA			: '',
			NIKATASAN1		: user_nik,
			NIKPERSONALIA	: nik_hrd,
			STATUSIJIN		: 'A'
		}, model);
		this.getListpermohonanijin().getStore().insert(0, r);
		this.getListpermohonanijin().rowEditing.startEdit(0,0);
		var task = new Ext.util.DelayedTask(function(){
			thisme.getListpermohonanijin().columns[1].field.setValue(null);
			thisme.getListpermohonanijin().columns[1].field.focus(false, true);
		});
		task.delay(500);
		
	},
	
	enableDelete: function(dataview, selections){
		this.getListpermohonanijin().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListpermohonanijin: function(me, record, item, index, e){
		var getListpermohonanijin = this.getListpermohonanijin();

		if (record.data.NIKATASAN1 == user_nik) {
			if (record.data.STATUSIJIN != 'A') {
				getListpermohonanijin.columns[1].field.setReadOnly(true);//NIK
				getListpermohonanijin.columns[11].field.setReadOnly(true);//STATUSIJIN
				getListpermohonanijin.columns[2].field.setReadOnly(true);//JENISABSEN
				getListpermohonanijin.columns[3].field.setReadOnly(true);//TANGGAL
				getListpermohonanijin.columns[4].field.setReadOnly(true);//JAMDARI
				getListpermohonanijin.columns[5].field.setReadOnly(true);//JAMSAMPAI
				getListpermohonanijin.columns[6].field.setReadOnly(true);//KEMBALI
				getListpermohonanijin.columns[7].field.setReadOnly(true);//AMBILCUTI
				getListpermohonanijin.columns[10].field.setReadOnly(true);//NIKPERSONALIA
			} else{
				getListpermohonanijin.columns[1].field.setReadOnly(false);//NIK	
				getListpermohonanijin.columns[10].field.setReadOnly(true);//NIKPERSONALIA
				getListpermohonanijin.columns[11].field.setReadOnly(true);//STATUSIJIN
				getListpermohonanijin.columns[2].field.setReadOnly(false);//JENISABSEN
				getListpermohonanijin.columns[3].field.setReadOnly(false);//TANGGAL
				getListpermohonanijin.columns[4].field.setReadOnly(false);//JAMDARI
			};
		} else if (record.data.NIKPERSONALIA == user_nik) {
			getListpermohonanijin.columns[1].field.setReadOnly(true);//NIK
			getListpermohonanijin.columns[10].field.setReadOnly(true);//NIKPERSONALIA
			getListpermohonanijin.columns[11].field.setReadOnly(false);//STATUSIJIN
			getListpermohonanijin.columns[2].field.setReadOnly(false);//JENISABSEN
			getListpermohonanijin.columns[3].field.setReadOnly(false);//TANGGAL
			getListpermohonanijin.columns[4].field.setReadOnly(true);//JAMDARI
		};
		/*var getPERMOHONANIJIN		= this.getPERMOHONANIJIN();
		var getListpermohonanijin	= this.getListpermohonanijin();
		var getV_permohonanijin_form= this.getV_permohonanijin_form(),
			form			= getV_permohonanijin_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_permohonanijin_form.down('#NOIJIN_field').setReadOnly(true);
		
		getV_permohonanijin_form.loadRecord(record);
		
		if(getV_permohonanijin_form.down('#NIKATASAN1_field').getValue() == user_nik)
		{
			if(getV_permohonanijin_form.down('#STATUSIJIN_field').getValue() != 'A')
			{
				getV_permohonanijin_form.down('#NIK_field').setReadOnly(true);	
				getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(true);		
				getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(true);			
				getV_permohonanijin_form.down('#JENISABSEN_field').setReadOnly(true);			
				getV_permohonanijin_form.down('#TANGGAL_field').setReadOnly(true);				
				getV_permohonanijin_form.down('#JAMDARI_field').setReadOnly(true);				
				getV_permohonanijin_form.down('#JAMSAMPAI_field').setReadOnly(true);					
				getV_permohonanijin_form.down('#KEMBALI_field').setReadOnly(true);						
				getV_permohonanijin_form.down('#AMBILCUTI_field').setReadOnly(true);						
				getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(true);	
			}
			else
			{
				getV_permohonanijin_form.down('#NIK_field').setReadOnly(false);	
				getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(true);		
				getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(true);			
				getV_permohonanijin_form.down('#JENISABSEN_field').setReadOnly(false);			
				getV_permohonanijin_form.down('#TANGGAL_field').setReadOnly(false);				
				getV_permohonanijin_form.down('#JAMDARI_field').setReadOnly(false);
			}
		}
		else if(getV_permohonanijin_form.down('#NIKPERSONALIA_field').getValue() == user_nik)
		{
			getV_permohonanijin_form.down('#NIK_field').setReadOnly(true);	
			getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(true);		
			getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(false);			
			getV_permohonanijin_form.down('#JENISABSEN_field').setReadOnly(false);			
			getV_permohonanijin_form.down('#TANGGAL_field').setReadOnly(false);				
			getV_permohonanijin_form.down('#JAMDARI_field').setReadOnly(true);	
		}
		
		getListpermohonanijin.setDisabled(true);
		getV_permohonanijin_form.setDisabled(false);
		getPERMOHONANIJIN.setActiveTab(getV_permohonanijin_form);*/
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpermohonanijin().getStore();
		var selection = this.getListpermohonanijin().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NOIJIN" = "'+selection.data.NOIJIN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListpermohonanijin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_permohonanijin/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpermohonanijin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_permohonanijin/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/permohonanijin.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpermohonanijin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_permohonanijin/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/permohonanijin.html','permohonanijin_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	},
	
	saveV_permohonanijin_form: function(){
		var getPERMOHONANIJIN		= this.getPERMOHONANIJIN();
		var getListpermohonanijin 	= this.getListpermohonanijin();
		var getV_permohonanijin_form= this.getV_permohonanijin_form(),
			form			= getV_permohonanijin_form.getForm(),
			values			= getV_permohonanijin_form.getValues();
		var store 			= this.getStore('s_permohonanijin');
		
		if(getV_permohonanijin_form.down('#NIKATASAN1_field').getValue() == user_nik)
		{
			getV_permohonanijin_form.down('#NIK_field').setReadOnly(false);	
			getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(false);		
			getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(true);			
			getV_permohonanijin_form.down('#JENISABSEN_field').setReadOnly(false);			
			getV_permohonanijin_form.down('#TANGGAL_field').setReadOnly(false);				
			getV_permohonanijin_form.down('#JAMDARI_field').setReadOnly(false);	
		}
		else if(getV_permohonanijin_form.down('#NIKPERSONALIA_field').getValue() == user_nik)
		{
			getV_permohonanijin_form.down('#NIK_field').setReadOnly(true);	
			getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(true);		
			getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(false);			
			getV_permohonanijin_form.down('#JENISABSEN_field').setReadOnly(false);			
			getV_permohonanijin_form.down('#TANGGAL_field').setReadOnly(false);				
			getV_permohonanijin_form.down('#JAMDARI_field').setReadOnly(true);	
		}
		
		if (form.isValid()) {
			values.JENISPERMOHONAN = 'ijin'
			values.JMLHARI = 1;
			var jsonData = Ext.encode(values);
			
			if(getV_permohonanijin_form.down('#NIKATASAN1_field').getValue() == user_nik)
			{
				if(values.SISA == 0 && values.JENISABSEN != 'IP')
				{
					/*Ext.Msg.show({
						title:'Ambil Cuti',
						msg: 'Sisa Cuti sudah habis, Potong Upah Pokok ?',
						buttons: Ext.Msg.YESNO,
						icon: Ext.Msg.INFO,
						fn:function(buttonText){
							if(buttonText == 'yes')
							{
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_permohonanijin/save',
									params: {data: jsonData},
									success: function(response){
										store.reload({
											callback: function(){
												var newRecordIndex = store.findBy(
													function(record, id) {
														if (record.get('NOIJIN') === values.NOIJIN) {
															return true;
														}
														return false;
													}
												);
												// getListpermohonanijin.getView().select(recordIndex);
												getListpermohonanijin.getSelectionModel().select(newRecordIndex);
											}
										});
										
										getV_permohonanijin_form.setDisabled(true);
										getListpermohonanijin.setDisabled(false);
										getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
									}
								});
							}
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
						}
					});*/
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === values.NOIJIN) {
												return true;
											}
											return false;
										}
									);
									// getListpermohonanijin.getView().select(recordIndex); 
									getListpermohonanijin.getSelectionModel().select(newRecordIndex);
								}
							});
							
							getV_permohonanijin_form.setDisabled(true);
							getListpermohonanijin.setDisabled(false);
							getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
						}
					});
				}
				else if(values.SISA > 0 && values.JENISABSEN != 'IP')
				{
					/*Ext.Msg.show({
						title:'Ambil Cuti',
						msg: 'Ambil Sisa Cuti ?',
						buttons: Ext.Msg.YESNO,
						icon: Ext.Msg.INFO,
						fn:function(buttonText){
							if(buttonText == 'yes')
							{
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_permohonanijin/save',
									params: {data: jsonData},
									success: function(response){
										store.reload({
											callback: function(){
												var newRecordIndex = store.findBy(
													function(record, id) {
														if (record.get('NOIJIN') === values.NOIJIN) {
															return true;
														}
														return false;
													}
												);
												// getListpermohonanijin.getView().select(recordIndex); 
												getListpermohonanijin.getSelectionModel().select(newRecordIndex);
											}
										});
										
										getV_permohonanijin_form.setDisabled(true);
										getListpermohonanijin.setDisabled(false);
										getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
									}
								});
							}
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
						}
					});*/
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === values.NOIJIN) {
												return true;
											}
											return false;
										}
									);
									// getListpermohonanijin.getView().select(recordIndex); 
									getListpermohonanijin.getSelectionModel().select(newRecordIndex);
								}
							});
							
							getV_permohonanijin_form.setDisabled(true);
							getListpermohonanijin.setDisabled(false);
							getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
						}
					});
				}
				else
				{
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === values.NOIJIN) {
												return true;
											}
											return false;
										}
									);
									/* getListpermohonanijin.getView().select(recordIndex); */
									getListpermohonanijin.getSelectionModel().select(newRecordIndex);
								}
							});
							
							getV_permohonanijin_form.setDisabled(true);
							getListpermohonanijin.setDisabled(false);
							getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
						}
					});
				}
			}
			else if(getV_permohonanijin_form.down('#NIKPERSONALIA_field').getValue() == user_nik)
			{
				if(values.SISA == 0 && values.JENISABSEN != 'IP')
				{
					//PENDING
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === values.NOIJIN) {
												return true;
											}
											return false;
										}
									);
									// getListpermohonanijin.getView().select(recordIndex); 
									getListpermohonanijin.getSelectionModel().select(newRecordIndex);
								}
							});
							
							//Proses pada CUTITAHUNAN 
							
							getV_permohonanijin_form.setDisabled(true);
							getListpermohonanijin.setDisabled(false);
							getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
						}
					});
					/*Ext.Msg.show({
						title:'Ambil Cuti',
						msg: 'Sisa Cuti sudah habis, Potong Upah Pokok ?',
						buttons: Ext.Msg.YESNO,
						icon: Ext.Msg.INFO,
						fn:function(buttonText){
							if(buttonText == 'yes')
							{
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_public_function/save',
									params: {data: jsonData},
									success: function(response){
										store.reload({
											callback: function(){
												var newRecordIndex = store.findBy(
													function(record, id) {
														if (record.get('NOIJIN') === values.NOIJIN) {
															return true;
														}
														return false;
													}
												);
												// getListpermohonanijin.getView().select(recordIndex); 
												getListpermohonanijin.getSelectionModel().select(newRecordIndex);
											}
										});
										
										//Proses pada CUTITAHUNAN 
										
										getV_permohonanijin_form.setDisabled(true);
										getListpermohonanijin.setDisabled(false);
										getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
									}
								});
							}
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
						}
					});*/
				}
				else if(values.SISA > 0 && values.JENISABSEN != 'IP')
				{
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === values.NOIJIN) {
												return true;
											}
											return false;
										}
									);
									// getListpermohonanijin.getView().select(recordIndex);
									getListpermohonanijin.getSelectionModel().select(newRecordIndex);
								}
							});
							
							getV_permohonanijin_form.setDisabled(true);
							getListpermohonanijin.setDisabled(false);
							getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
						}
					});
					/*Ext.Msg.show({
						title:'Ambil Cuti',
						msg: 'Ambil Sisa Cuti ?',
						buttons: Ext.Msg.YESNO,
						icon: Ext.Msg.INFO,
						fn:function(buttonText){
							if(buttonText == 'yes')
							{
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_public_function/save',
									params: {data: jsonData},
									success: function(response){
										store.reload({
											callback: function(){
												var newRecordIndex = store.findBy(
													function(record, id) {
														if (record.get('NOIJIN') === values.NOIJIN) {
															return true;
														}
														return false;
													}
												);
												// getListpermohonanijin.getView().select(recordIndex);
												getListpermohonanijin.getSelectionModel().select(newRecordIndex);
											}
										});
										
										getV_permohonanijin_form.setDisabled(true);
										getListpermohonanijin.setDisabled(false);
										getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
									}
								});
							}
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
						}
					});*/
				}
				else
				{
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === values.NOIJIN) {
												return true;
											}
											return false;
										}
									);
									// getListpermohonanijin.getView().select(recordIndex); 
									getListpermohonanijin.getSelectionModel().select(newRecordIndex);
								}
							});
							
							getV_permohonanijin_form.setDisabled(true);
							getListpermohonanijin.setDisabled(false);
							getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
						}
					});
				}
			}
			
			
			
		}
	},
	
	createV_permohonanijin_form: function(){
		var getPERMOHONANIJIN		= this.getPERMOHONANIJIN();
		var getListpermohonanijin 	= this.getListpermohonanijin();
		var getV_permohonanijin_form= this.getV_permohonanijin_form(),
			form			= getV_permohonanijin_form.getForm(),
			values			= getV_permohonanijin_form.getValues();
		var store 			= this.getStore('s_permohonanijin');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_permohonanijin/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_permohonanijin_form.setDisabled(true);
					getListpermohonanijin.setDisabled(false);
					getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
				}
			});
		}
	},
	
	cancelV_permohonanijin_form: function(){
		var getPERMOHONANIJIN		= this.getPERMOHONANIJIN();
		var getListpermohonanijin	= this.getListpermohonanijin();
		var getV_permohonanijin_form= this.getV_permohonanijin_form(),
			form			= getV_permohonanijin_form.getForm();
			
		form.reset();
		getV_permohonanijin_form.setDisabled(true);
		getListpermohonanijin.setDisabled(false);
		getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
	}
	
});