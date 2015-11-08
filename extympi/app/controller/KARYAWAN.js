Ext.define('YMPI.controller.KARYAWAN',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_karyawan','MUTASI.v_karyawan_form','MUTASI.v_keluarga','MUTASI.v_skill'
			,'MUTASI.v_riwayatkerja','MUTASI.v_riwayatkerjaympi','MUTASI.v_riwayattraining'
			,'MUTASI.v_riwayatsehat','MUTASI.v_penghargaan'],
	models: ['m_karyawan','m_keluarga','m_skill','m_riwayatkerja','m_riwayatkerjaympi','m_riwayattraining'
			 ,'m_riwayatsehat','m_penghargaan'],
	stores: ['s_karyawan','s_keluarga','s_skill','s_riwayatkerja','s_riwayatkerjaympi','s_riwayattraining'
			 ,'s_riwayatsehat','s_penghargaan'],
	
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
	}, {
		ref: 'Listskill',
		selector: 'Listskill'
	}, {
		ref: 'Listriwayatkerja',
		selector: 'Listriwayatkerja'
	}, {
		ref: 'Listriwayatkerjaympi',
		selector: 'Listriwayatkerjaympi'
	}, {
		ref: 'Listriwayattraining',
		selector: 'Listriwayattraining'
	}, {
		ref: 'Listriwayatsehat',
		selector: 'Listriwayatsehat'
	}, {
		ref: 'Listpenghargaan',
		selector: 'Listpenghargaan'
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
				//this.getListkeluarga().down('#btndelete').setDisabled(false);
				this.getListkeluarga().down('#btnxexcel').setDisabled(false);
				this.getListkeluarga().down('#btnxpdf').setDisabled(false);
				this.getListkeluarga().down('#btnprint').setDisabled(false);
				this.getListkeluarga().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
				
				this.getListskill().down('#btncreate').setDisabled(false);
				//this.getListskill().down('#btndelete').setDisabled(false);
				this.getListskill().down('#btnxexcel').setDisabled(false);
				this.getListskill().down('#btnxpdf').setDisabled(false);
				this.getListskill().down('#btnprint').setDisabled(false);
				this.getListskill().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
				
				this.getListriwayatkerja().down('#btncreate').setDisabled(false);
				//this.getListriwayatkerja().down('#btndelete').setDisabled(false);
				this.getListriwayatkerja().down('#btnxexcel').setDisabled(false);
				this.getListriwayatkerja().down('#btnxpdf').setDisabled(false);
				this.getListriwayatkerja().down('#btnprint').setDisabled(false);
				this.getListriwayatkerja().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
				
				this.getListriwayatkerjaympi().down('#btncreate').setDisabled(false);
				//this.getListriwayatkerjaympi().down('#btndelete').setDisabled(false);
				this.getListriwayatkerjaympi().down('#btnxexcel').setDisabled(false);
				this.getListriwayatkerjaympi().down('#btnxpdf').setDisabled(false);
				this.getListriwayatkerjaympi().down('#btnprint').setDisabled(false);
				this.getListriwayatkerjaympi().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
				
				this.getListriwayattraining().down('#btncreate').setDisabled(false);
				//this.getListriwayattraining().down('#btndelete').setDisabled(false);
				this.getListriwayattraining().down('#btnxexcel').setDisabled(false);
				this.getListriwayattraining().down('#btnxpdf').setDisabled(false);
				this.getListriwayattraining().down('#btnprint').setDisabled(false);
				this.getListriwayattraining().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
				
				this.getListriwayatsehat().down('#btncreate').setDisabled(false);
				//this.getListriwayatsehat().down('#btndelete').setDisabled(false);
				this.getListriwayatsehat().down('#btnxexcel').setDisabled(false);
				this.getListriwayatsehat().down('#btnxpdf').setDisabled(false);
				this.getListriwayatsehat().down('#btnprint').setDisabled(false);
				this.getListriwayatsehat().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
				
				this.getListpenghargaan().down('#btncreate').setDisabled(false);
				//this.getListpenghargaan().down('#btndelete').setDisabled(false);
				this.getListpenghargaan().down('#btnxexcel').setDisabled(false);
				this.getListpenghargaan().down('#btnxpdf').setDisabled(false);
				this.getListpenghargaan().down('#btnprint').setDisabled(false);
				this.getListpenghargaan().getStore().load({
					params: {
						NIK: selection_dtkaryawan.NIK
					}
				});
			}else{
				this.getListkeluarga().down('#btncreate').setDisabled(true);
				//this.getListkeluarga().down('#btndelete').setDisabled(true);
				this.getListkeluarga().down('#btnxexcel').setDisabled(true);
				this.getListkeluarga().down('#btnxpdf').setDisabled(true);
				this.getListkeluarga().down('#btnprint').setDisabled(true);
				
				this.getListskill().down('#btncreate').setDisabled(true);
				//this.getListskill().down('#btndelete').setDisabled(true);
				this.getListskill().down('#btnxexcel').setDisabled(true);
				this.getListskill().down('#btnxpdf').setDisabled(true);
				this.getListskill().down('#btnprint').setDisabled(true);
				
				this.getListriwayatkerja().down('#btncreate').setDisabled(true);
				//this.getListriwayatkerja().down('#btndelete').setDisabled(true);
				this.getListriwayatkerja().down('#btnxexcel').setDisabled(true);
				this.getListriwayatkerja().down('#btnxpdf').setDisabled(true);
				this.getListriwayatkerja().down('#btnprint').setDisabled(true);
				
				this.getListriwayatkerjaympi().down('#btncreate').setDisabled(true);
				//this.getListriwayatkerjaympi().down('#btndelete').setDisabled(true);
				this.getListriwayatkerjaympi().down('#btnxexcel').setDisabled(true);
				this.getListriwayatkerjaympi().down('#btnxpdf').setDisabled(true);
				this.getListriwayatkerjaympi().down('#btnprint').setDisabled(true);
				
				this.getListriwayattraining().down('#btncreate').setDisabled(true);
				//this.getListriwayattraining().down('#btndelete').setDisabled(true);
				this.getListriwayattraining().down('#btnxexcel').setDisabled(true);
				this.getListriwayattraining().down('#btnxpdf').setDisabled(true);
				this.getListriwayattraining().down('#btnprint').setDisabled(true);
				
				this.getListriwayatsehat().down('#btncreate').setDisabled(true);
				//this.getListriwayatsehat().down('#btndelete').setDisabled(true);
				this.getListriwayatsehat().down('#btnxexcel').setDisabled(true);
				this.getListriwayatsehat().down('#btnxpdf').setDisabled(true);
				this.getListriwayatsehat().down('#btnprint').setDisabled(true);
				
				this.getListpenghargaan().down('#btncreate').setDisabled(true);
				//this.getListpenghargaan().down('#btndelete').setDisabled(true);
				this.getListpenghargaan().down('#btnxexcel').setDisabled(true);
				this.getListpenghargaan().down('#btnxpdf').setDisabled(true);
				this.getListpenghargaan().down('#btnprint').setDisabled(true);
			}
		}else{
			this.getListkaryawan().down('#btndelete').setDisabled(!selections.length);
			
			this.getListkeluarga().down('#btncreate').setDisabled(true);
			//this.getListkeluarga().down('#btndelete').setDisabled(true);
			this.getListkeluarga().down('#btnxexcel').setDisabled(true);
			this.getListkeluarga().down('#btnxpdf').setDisabled(true);
			this.getListkeluarga().down('#btnprint').setDisabled(true);
			this.getListkeluarga().getStore().removeAll();
			
			this.getListskill().down('#btncreate').setDisabled(true);
			//this.getListskill().down('#btndelete').setDisabled(true);
			this.getListskill().down('#btnxexcel').setDisabled(true);
			this.getListskill().down('#btnxpdf').setDisabled(true);
			this.getListskill().down('#btnprint').setDisabled(true);
			this.getListskill().getStore().removeAll();
			
			this.getListriwayatkerja().down('#btncreate').setDisabled(true);
			//this.getListriwayatkerja().down('#btndelete').setDisabled(true);
			this.getListriwayatkerja().down('#btnxexcel').setDisabled(true);
			this.getListriwayatkerja().down('#btnxpdf').setDisabled(true);
			this.getListriwayatkerja().down('#btnprint').setDisabled(true);
			this.getListriwayatkerja().getStore().removeAll();
			
			this.getListriwayatkerjaympi().down('#btncreate').setDisabled(true);
			//this.getListriwayatkerjaympi().down('#btndelete').setDisabled(true);
			this.getListriwayatkerjaympi().down('#btnxexcel').setDisabled(true);
			this.getListriwayatkerjaympi().down('#btnxpdf').setDisabled(true);
			this.getListriwayatkerjaympi().down('#btnprint').setDisabled(true);
			this.getListriwayatkerjaympi().getStore().removeAll();
			
			this.getListriwayattraining().down('#btncreate').setDisabled(true);
			//this.getListriwayattraining().down('#btndelete').setDisabled(true);
			this.getListriwayattraining().down('#btnxexcel').setDisabled(true);
			this.getListriwayattraining().down('#btnxpdf').setDisabled(true);
			this.getListriwayattraining().down('#btnprint').setDisabled(true);
			this.getListriwayattraining().getStore().removeAll();
			
			this.getListriwayatsehat().down('#btncreate').setDisabled(true);
			//this.getListriwayatsehat().down('#btndelete').setDisabled(true);
			this.getListriwayatsehat().down('#btnxexcel').setDisabled(true);
			this.getListriwayatsehat().down('#btnxpdf').setDisabled(true);
			this.getListriwayatsehat().down('#btnprint').setDisabled(true);
			this.getListriwayatsehat().getStore().removeAll();
			
			this.getListpenghargaan().down('#btncreate').setDisabled(true);
			//this.getListpenghargaan().down('#btndelete').setDisabled(true);
			this.getListpenghargaan().down('#btnxexcel').setDisabled(true);
			this.getListpenghargaan().down('#btnxpdf').setDisabled(true);
			this.getListpenghargaan().down('#btnprint').setDisabled(true);
			this.getListpenghargaan().getStore().removeAll();
		}
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
		/*var getstore = this.getListkaryawan().getStore();
		var selection = this.getListkaryawan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NIK" = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}*/
		var mainthis   = this;
		var getstore   = this.getListkaryawan().getStore();
		var selections = this.getListkaryawan().getSelectionModel().getSelection();
		var selection  = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var jsonData   = Ext.encode(Ext.pluck(selections, 'data'));

		var STATUS_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'STATUS_field',
			fieldLabel: 'Non Aktif, karena Status <font color=red>(*)</font>',
			name: 'STATUS', /* column name of table */
			store: Ext.create('Ext.data.Store', {
	    	    fields: ['value', 'display'],
	    	    data : [
	    	        {"value":"P", "display":"Pensiun"},
	    	        {"value":"H", "display":"PHK"},
	    	        {"value":"M", "display":"Meninggal"}
	    	    ]
	    	}),
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			allowBlank: false,
			editable: false,
			width: 120
		});

		var form = Ext.widget('form', {
	        width: 340,
	        border: false,
            bodyPadding: 10,

	        fieldDefaults: {
	            labelAlign: 'left',
	            labelWidth: 180,
	            anchor: '100%'
	        },

	        items: [STATUS_field],
	        buttons: [{
                text: 'Cancel',
                handler: function() {
                    this.up('form').getForm().reset();
                    this.up('window').hide();
                }
            },{
                text: 'Save',
                handler: function() {
                	var statusfield = STATUS_field.getValue();
                	var getform = this.up('form').getForm();
                	var getwindow = this.up('window');
                	if (statusfield) {
                		mainthis.mutasiRecordKaryawan(jsonData,statusfield,getform,getwindow);
                	} else{
                		this.up('form').down('#STATUS_field').expand();
                	};
                }
            }]
	    });
		var win = Ext.widget('window', {
            title: '['+selection.data.NIK+'] '+selection.data.NAMAKAR,
            closeAction: 'hide',
            closable: false,
            width: 345,
            // height: 400,
            layout: 'fit',
            resizable: false,
            modal: true,
            items: form,
            defaultFocus: 'firstName'
        });
        win.show();
	},

	mutasiRecordKaryawan: function(jsonData,statusfield,getform,getwindow){
		var getstore = this.getListkaryawan().getStore();
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_karyawan/nonaktifKaryawan',
			params: {
				data: jsonData,
				status: statusfield
			},
			success: function(response){
				getform.reset();
				getwindow.hide();
				
				getstore.load();
				var objResponse = Ext.JSON.decode(response.responseText);
				Ext.MessageBox.show({
					title: 'Info',
					msg: objResponse.message,
					buttons: Ext.MessageBox.OK,
					icon: Ext.Msg.INFO
				});
			}
		});
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
			console.log(jsonData);
			form.submit({
				method: 'POST',
				url: 'c_karyawan/save',
				params: {data: jsonData},
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