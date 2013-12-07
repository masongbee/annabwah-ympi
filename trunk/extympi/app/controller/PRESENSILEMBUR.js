Ext.define('YMPI.controller.PRESENSILEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_presensilembur','TRANSAKSI.v_presensilembur_form'],
	models: ['m_presensilembur'],
	stores: ['s_presensilembur'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpresensilembur',
		selector: 'Listpresensilembur'
	}, {
		ref: 'v_presensilembur_form',
		selector: 'v_presensilembur_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_presensilembur_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_presensilembur_form #create'
	}, {
		ref: 'PRESENSILEMBUR',
		selector: 'PRESENSILEMBUR'
	}],


	init: function(){
		this.control({
			'PRESENSILEMBUR': {
				'afterrender': this.presensilemburAfterRender
			},
			'v_presensilembur_form': {
				'afterrender': this.splemburAfterRender
			},
			'Listpresensilembur': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListpresensilembur
			},
			'Listpresensilembur button[action=create]': {
				click: this.createRecord
			},
			'Listpresensilembur button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpresensilembur button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpresensilembur button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpresensilembur button[action=print]': {
				click: this.printRecords
			},
			'v_presensilembur_form button[action=save]': {
				click: this.saveV_presensilembur_form
			},
			'v_presensilembur_form button[action=create]': {
				click: this.saveV_presensilembur_form
			},
			'v_presensilembur_form button[action=cancel]': {
				click: this.cancelV_presensilembur_form
			}
		});
	},
	
	presensilemburAfterRender: function(){
		var presensilemburStore = this.getListpresensilembur().getStore();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm(),
			values			= getV_presensilembur_form.getValues();
		presensilemburStore.load();
		form.reset();
	},
	
	splemburAfterRender: function(){
		this.presensilemburAfterRender();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm(),
			values			= getV_presensilembur_form.getValues();
		getV_presensilembur_form.down('#NIK_field').focus(false,100);
		//form.findField('NIK').focus();
	},
	
	createRecord: function(){
		var getListpresensilembur	= this.getListpresensilembur();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListpresensilembur.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_presensilembur_form.down('#NIK_field').setReadOnly(false);
		getV_presensilembur_form.down('#TJMASUK_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		//getV_presensilembur_form.setDisabled(false);
		
		//this.getPRESENSILEMBUR().setActiveTab(getV_presensilembur_form);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpresensilembur().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListpresensilembur: function(me, record, item, index, e){
		var getPRESENSILEMBUR		= this.getPRESENSILEMBUR();
		var getListpresensilembur	= this.getListpresensilembur();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_presensilembur_form.down('#NIK_field').setReadOnly(true);
		getV_presensilembur_form.down('#TJMASUK_field').setReadOnly(true);		
		getV_presensilembur_form.loadRecord(record);
		
		//getListpresensilembur.setDisabled(true);
		//getV_presensilembur_form.setDisabled(false);
		//getPRESENSILEMBUR.setActiveTab(getV_presensilembur_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpresensilembur().getStore();
		var selection = this.getListpresensilembur().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NIK" = "'+selection.data.NIK+'","TJMASUK" = "'+selection.data.TJMASUK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListpresensilembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensilembur/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpresensilembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensilembur/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/presensilembur.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpresensilembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_presensilembur/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/presensilembur.html','presensilembur_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_presensilembur_form: function(){
		var getPRESENSILEMBUR		= this.getPRESENSILEMBUR();
		var getListpresensilembur 	= this.getListpresensilembur();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm(),
			values			= getV_presensilembur_form.getValues();
		var store 			= this.getStore('s_presensilembur');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_presensilembur/save',
				params: {data: jsonData},
				success: function(response){
					var obj = Ext.JSON.decode(response.responseText);
					console.info(obj);
					Ext.Msg.show({
						title: 'Presensi Lembur...',
						msg: obj.message,
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.INFO,
						buttons: Ext.Msg.OK,
						fn:function(){
							if(obj.success)
							{
								store.reload({
									callback: function(){
										var newRecordIndex = store.findBy(
											function(record, id) {
												if (record.get('NIK') === values.NIK && (new Date(record.get('TJMASUK'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(values.TJMASUK)).format('yyyy-mm-dd hh:nn:ss')) {
													return true;
												}
												return false;
											}
										);
										/* getListpresensilembur.getView().select(recordIndex); */
										getListpresensilembur.getSelectionModel().select(newRecordIndex);
									}
								});
							}
							getV_presensilembur_form.down('#NIK_field').focus(false,100);
						}
					});
					
					
					//getV_presensilembur_form.setDisabled(true);
					//getListpresensilembur.setDisabled(false);
					//getPRESENSILEMBUR.setActiveTab(getListpresensilembur);
				},
				failure: function(response){
					var obj = Ext.JSON.decode(response.responseText);
					console.info(obj);
					Ext.Msg.show({
						title: 'Presensi Lembur...',
						msg: response.statusText,
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.INFO,
						buttons: Ext.Msg.OK
					});
				}
			});
			form.reset();
			getV_presensilembur_form.down('#NIK_field').focus(false,100);
		}
	},
	
	createV_presensilembur_form: function(){
		var getPRESENSILEMBUR		= this.getPRESENSILEMBUR();
		var getListpresensilembur 	= this.getListpresensilembur();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm(),
			values			= getV_presensilembur_form.getValues();
		var store 			= this.getStore('s_presensilembur');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_presensilembur/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					//getV_presensilembur_form.setDisabled(true);
					//getListpresensilembur.setDisabled(false);
					//getPRESENSILEMBUR.setActiveTab(getListpresensilembur);
				}
			});
			form.reset();
			getV_presensilembur_form.down('#NIK_field').focus(false,100);
		}
		console.info(getV_presensilembur_form);
	},
	
	cancelV_presensilembur_form: function(){
		var getPRESENSILEMBUR		= this.getPRESENSILEMBUR();
		var getListpresensilembur	= this.getListpresensilembur();
		var getV_presensilembur_form= this.getV_presensilembur_form(),
			form			= getV_presensilembur_form.getForm();
		
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		//getListpresensilembur.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_presensilembur_form.down('#NIK_field').setReadOnly(false);
		getV_presensilembur_form.down('#TJMASUK_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_presensilembur_form.down('#NIK_field').focus(false,100);
		//getV_presensilembur_form.setDisabled(true);
		//getListpresensilembur.setDisabled(false);
		//getPRESENSILEMBUR.setActiveTab(getListpresensilembur);
	}
	
});