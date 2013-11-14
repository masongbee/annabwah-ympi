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
		var getListpermohonanijin	= this.getListpermohonanijin();
		var getV_permohonanijin_form= this.getV_permohonanijin_form(),
			form			= getV_permohonanijin_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListpermohonanijin.setDisabled(true);
        
		/* form-panel */
		form.reset();
		//getV_permohonanijin_form.down('#NOIJIN_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_permohonanijin_form.setDisabled(false);
		
		Ext.Ajax.request({
			url: 'c_permohonanijin/getNIK',
			params: {
				NIK: user_nik
			},
			success: function(response){
				var msg = Ext.decode(response.responseText);
				//console.info(msg);
				if(msg.data != '')
				{
					getV_permohonanijin_form.down('#NIKATASAN1_field').setValue(msg.data[0].NAMA);
				}
			}
		});
		getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(true);
		
		this.getPERMOHONANIJIN().setActiveTab(getV_permohonanijin_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListpermohonanijin().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListpermohonanijin: function(me, record, item, index, e){
		var getPERMOHONANIJIN		= this.getPERMOHONANIJIN();
		var getListpermohonanijin	= this.getListpermohonanijin();
		var getV_permohonanijin_form= this.getV_permohonanijin_form(),
			form			= getV_permohonanijin_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_permohonanijin_form.down('#NOIJIN_field').setReadOnly(true);
		
		getV_permohonanijin_form.loadRecord(record);
		console.info(record);

		if(getV_permohonanijin_form.down('#NIKATASAN1_field').getValue() == user_nik)
		{
			getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(true);	
		}
		else
		{
			getV_permohonanijin_form.down('#NIK_field').setReadOnly(true);	
			getV_permohonanijin_form.down('#NIKPERSONALIA_field').setReadOnly(true);		
			getV_permohonanijin_form.down('#STATUSIJIN_field').setReadOnly(false);			
			getV_permohonanijin_form.down('#JENISABSEN_field').setReadOnly(true);			
			getV_permohonanijin_form.down('#TANGGAL_field').setReadOnly(true);				
			getV_permohonanijin_form.down('#JAMDARI_field').setReadOnly(true);	
		}
		
		getListpermohonanijin.setDisabled(true);
		getV_permohonanijin_form.setDisabled(false);
		getPERMOHONANIJIN.setActiveTab(getV_permohonanijin_form);
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
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			if(getV_permohonanijin_form.down('#NIKATASAN1_field').getValue() == user_nik)
			{
				if(values.SISA == 0 && values.JENISABSEN != 'IP')
				{
					Ext.Msg.show({
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
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
						}
					});
				}
				else if(values.SISA > 0 && values.JENISABSEN != 'IP')
				{
					Ext.Msg.show({
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
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
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
					Ext.Msg.show({
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
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
						}
					});
				}
				else if(values.SISA > 0 && values.JENISABSEN != 'IP')
				{
					Ext.Msg.show({
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
							else if(buttonText == 'no')
							{
								form.reset();
								getV_permohonanijin_form.setDisabled(true);
								getListpermohonanijin.setDisabled(false);
								getPERMOHONANIJIN.setActiveTab(getListpermohonanijin);
							}
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