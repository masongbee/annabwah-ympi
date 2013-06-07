Ext.define('YMPI.controller.KARYAWAN',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_karyawan','MUTASI.v_karyawan_form','MUTASI.v_keluarga'],
	models: ['m_karyawan','m_keluarga'],
	stores: ['s_karyawan','s_keluarga'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}, {
		ref: 'v_karyawan_form',
		selector: 'v_karyawan_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_karyawan_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_karyawan_form #create'
	}, {
		ref: 'KARYAWAN',
		selector: 'KARYAWAN #center'
	}, {
		ref: 'KARYAWANSOUTH',
		selector: 'KARYAWAN #south'
	}, {
		ref: 'Listkeluarga',
		selector: 'Listkeluarga'
	}],


	init: function(){
		this.control({
			'KARYAWAN': {
				'afterrender': this.afterrenderKaryawan
			},
			'Listkaryawan': {
				'selectionchange': this.enableDeleteKaryawan,
				'itemdblclick': this.updateListkaryawan
			},
			'Listkaryawan button[action=create]': {
				click: this.createRecordKaryawan
			},
			'Listkaryawan button[action=delete]': {
				click: this.deleteRecordKaryawan
			},
			'Listkaryawan button[action=xexcel]': {
				click: this.export2ExcelKaryawan
			},
			'Listkaryawan button[action=xpdf]': {
				click: this.export2PDFKaryawan
			},
			'Listkaryawan button[action=print]': {
				click: this.printRecordsKaryawan
			},
			'v_karyawan_form button[action=save]': {
				click: this.saveV_karyawan_form
			},
			'v_karyawan_form button[action=create]': {
				click: this.saveV_karyawan_form
			},
			'v_karyawan_form button[action=cancel]': {
				click: this.cancelV_karyawan_form
			}
		});
	},
	
	afterrenderKaryawan: function(){
		var karyawanStore = this.getListkaryawan().getStore();
		karyawanStore.load();
	},
	
	createRecordKaryawan: function(){
		var getListkaryawan	= this.getListkaryawan();
		var getV_karyawan_form= this.getV_karyawan_form(),
			form			= getV_karyawan_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListkaryawan.setDisabled(true);
        
		/* form-panel */
		form.reset();
		//getV_karyawan_form.down('#NIK_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_karyawan_form.setDisabled(false);
		
		this.getKARYAWAN().setActiveTab(getV_karyawan_form);
		this.getKARYAWANSOUTH().setVisible(false);
	},
	
	enableDeleteKaryawan: function(dataview, selections){
		
		
		if (selections.length) {
			var selection_dtkaryawan = selections[0].data;
			
			this.getListkaryawan().down('#btndelete').setDisabled(!selections.length);
			
			/* v_keluarga */
			if (selection_dtkaryawan.KAWIN != 'B') {
				this.getListkeluarga().down('#btncreate').setDisabled(false);
				this.getListkeluarga().down('#btndelete').setDisabled(false);
				this.getListkeluarga().down('#btnxexcel').setDisabled(false);
				this.getListkeluarga().down('#btnxpdf').setDisabled(false);
				this.getListkeluarga().down('#btnprint').setDisabled(false);
				this.getListkeluarga().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
			}else{
				this.getListkeluarga().down('#btncreate').setDisabled(true);
				this.getListkeluarga().down('#btndelete').setDisabled(true);
				this.getListkeluarga().down('#btnxexcel').setDisabled(true);
				this.getListkeluarga().down('#btnxpdf').setDisabled(true);
				this.getListkeluarga().down('#btnprint').setDisabled(true);
			}
		}else{
			this.getListkaryawan().down('#btndelete').setDisabled(!selections.length);
			
			this.getListkeluarga().down('#btncreate').setDisabled(true);
			this.getListkeluarga().down('#btndelete').setDisabled(true);
			this.getListkeluarga().down('#btnxexcel').setDisabled(true);
			this.getListkeluarga().down('#btnxpdf').setDisabled(true);
			this.getListkeluarga().down('#btnprint').setDisabled(true);
			this.getListkeluarga().getStore().removeAll();
		}
		
		
		/*  */
	},
	
	updateListkaryawan: function(me, record, item, index, e){
		var getKARYAWAN		= this.getKARYAWAN();
		var getListkaryawan	= this.getListkaryawan();
		var getV_karyawan_form= this.getV_karyawan_form(),
			form			= getV_karyawan_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_karyawan_form.down('#NIK_field').setReadOnly(true);		
		getV_karyawan_form.loadRecord(record);
		
		getListkaryawan.setDisabled(true);
		getV_karyawan_form.setDisabled(false);
		getKARYAWAN.setActiveTab(getV_karyawan_form);
		this.getKARYAWANSOUTH().setVisible(false);
	},
	
	deleteRecordKaryawan: function(dataview, selections){
		var getstore = this.getListkaryawan().getStore();
		var selection = this.getListkaryawan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NIK" = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2ExcelKaryawan: function(){
		var getstore = this.getListkaryawan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_karyawan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDFKaryawan: function(){
		var getstore = this.getListkaryawan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_karyawan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/karyawan.pdf', '_blank');
			}
		});
	},
	
	printRecordsKaryawan: function(){
		var getstore = this.getListkaryawan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_karyawan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/karyawan.html','karyawan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_karyawan_form: function(){
		console.log('save form');
		var getKARYAWAN			= this.getKARYAWAN();
		var getKARYAWANSOUTH	= this.getKARYAWANSOUTH();
		var getListkaryawan 	= this.getListkaryawan();
		var getV_karyawan_form	= this.getV_karyawan_form(),
			form				= getV_karyawan_form.getForm(),
			values				= getV_karyawan_form.getValues();
		var store 				= this.getStore('s_karyawan');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			form.submit({
				method: 'POST',
				url: 'c_karyawan/save',
				//params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NIK') === values.NIK) {
										return true;
									}
									return false;
								}
							);
							/* getListkaryawan.getView().select(recordIndex); */
							getListkaryawan.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_karyawan_form.setDisabled(true);
					getListkaryawan.setDisabled(false);
					getKARYAWAN.setActiveTab(getListkaryawan);
					getKARYAWANSOUTH.setVisible(true);
				}
			});
		}
	},
	
	createV_karyawan_form: function(){
		console.log('create form');
		var getKARYAWAN		= this.getKARYAWAN();
		var getListkaryawan 	= this.getListkaryawan();
		var getV_karyawan_form= this.getV_karyawan_form(),
			form			= getV_karyawan_form.getForm(),
			values			= getV_karyawan_form.getValues();
		var store 			= this.getStore('s_karyawan');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_karyawan/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_karyawan_form.setDisabled(true);
					getListkaryawan.setDisabled(false);
					getKARYAWAN.setActiveTab(getListkaryawan);
				}
			});
		}
	},
	
	cancelV_karyawan_form: function(){
		var getKARYAWAN			= this.getKARYAWAN();
		var getKARYAWANSOUTH	= this.getKARYAWANSOUTH();
		var getListkaryawan		= this.getListkaryawan();
		var getV_karyawan_form	= this.getV_karyawan_form(),
			form				= getV_karyawan_form.getForm();
			
		form.reset();
		getV_karyawan_form.setDisabled(true);
		getListkaryawan.setDisabled(false);
		getKARYAWAN.setActiveTab(getListkaryawan);
		getKARYAWANSOUTH.setVisible(true);
	}
	
});