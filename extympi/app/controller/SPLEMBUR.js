Ext.define('YMPI.controller.SPLEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_splembur','TRANSAKSI.v_splembur_form','TRANSAKSI.v_rencanalembur'],
	models: ['m_splembur','m_rencanalembur'],
	stores: ['s_splembur','s_rencanalembur'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listsplembur',
		selector: 'Listsplembur'
	}, {
		ref: 'v_splembur_form',
		selector: 'v_splembur_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_splembur_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_splembur_form #create'
	}, {
		ref: 'SPLEMBUR',
		selector: 'SPLEMBUR #center'
	}, {
		ref: 'Listrencanalembur',
		selector: 'Listrencanalembur'
	}],


	init: function(){
		this.control({
			'SPLEMBUR': {
				'afterrender': this.splemburAfterRender
			},
			'Listsplembur': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListsplembur
			},
			'Listrencanalembur': {
				'beforeedit': this.rLemburBedit
			},
			'Listsplembur button[action=create]': {
				click: this.createRecord
			},
			'Listsplembur button[action=delete]': {
				click: this.deleteRecord
			},
			'Listsplembur button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listsplembur button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listsplembur button[action=print]': {
				click: this.printRecords
			},
			'v_splembur_form button[action=save]': {
				click: this.saveV_splembur_form
			},
			'v_splembur_form button[action=create]': {
				click: this.saveV_splembur_form
			},
			'v_splembur_form button[action=cancel]': {
				click: this.cancelV_splembur_form
			}
		});
	},
	
	splemburAfterRender: function(){
		var splemburStore = this.getListsplembur().getStore();
		
		splemburStore.proxy.extraParams.nik = user_nik;
		splemburStore.load();
	},
	
	createRecord: function(){
		var getListsplembur	= this.getListsplembur();
		var getV_splembur_form= this.getV_splembur_form(),
			form			= getV_splembur_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListsplembur.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_splembur_form.down('#NIKSETUJU_field').setReadOnly(false);
		getV_splembur_form.down('#NIKPERSONALIA_field').setReadOnly(true);
			getV_splembur_form.down('#TGLSETUJU_field').setReadOnly(true);
			getV_splembur_form.down('#TGLPERSONALIA_field').setReadOnly(true);
		getV_splembur_form.down('#NOLEMBUR_field').setReadOnly(false);
		getV_splembur_form.down('#NIKUSUL_field').setValue(user_nik);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_splembur_form.setDisabled(false);
		
		this.getSPLEMBUR().setActiveTab(getV_splembur_form);		
	},
	
	//enableDelete: function(dataview, selections){
	//	this.getListsplembur().down('#btndelete').setDisabled(!selections.length);
	//},
	
	enableDelete: function(dataview, selections){
		var getV_splembur_form= this.getV_splembur_form(),
			form			= getV_splembur_form.getForm();
		
		console.info(selections[0].data);
		if (selections.length) {
			var select_spl = selections[0].data;
			
			this.getListsplembur().down('#btndelete').setDisabled(!selections.length);
			
			/* v_rencanalembur */
			if (select_spl.NOLEMBUR != null) {
				getV_splembur_form.down('#NIKSETUJU_field').setReadOnly(false);
				getV_splembur_form.down('#NIKPERSONALIA_field').setReadOnly(true);
				
				this.getListrencanalembur().down('#btncreate').setDisabled(false);
				this.getListrencanalembur().down('#btndelete').setDisabled(false);
				this.getListrencanalembur().down('#btnxexcel').setDisabled(false);
				this.getListrencanalembur().down('#btnxpdf').setDisabled(false);
				this.getListrencanalembur().down('#btnprint').setDisabled(false);
				this.getListrencanalembur().getStore().load({
					params: {
						NOLEMBUR: select_spl.NOLEMBUR
					}
				});
				console.info('Dipilih dari SPL : '+select_spl.NOLEMBUR);
			}else{
				this.getListrencanalembur().down('#btncreate').setDisabled(true);
				this.getListrencanalembur().down('#btndelete').setDisabled(true);
				this.getListrencanalembur().down('#btnxexcel').setDisabled(true);
				this.getListrencanalembur().down('#btnxpdf').setDisabled(true);
				this.getListrencanalembur().down('#btnprint').setDisabled(true);
			}
			
			if(select_spl.TGLSETUJU != null || select_spl.TGLPERSONALIA != null)
			{
				this.getListsplembur().down('#btndelete').setDisabled(true);
				this.getListrencanalembur().down('#btncreate').setDisabled(true);
				this.getListrencanalembur().down('#btndelete').setDisabled(true);
			}
			else if(select_spl.NIKSETUJU == user_nik || select_spl.NIKPERSONALIA == user_nik)
			{
				this.getListrencanalembur().down('#btncreate').setDisabled(true);
				this.getListrencanalembur().down('#btndelete').setDisabled(true);
			}
			
		}else{
			this.getListsplembur().down('#btndelete').setDisabled(!selections.length);
			this.getListrencanalembur().down('#btncreate').setDisabled(true);
			this.getListrencanalembur().down('#btndelete').setDisabled(true);
			this.getListrencanalembur().down('#btnxexcel').setDisabled(true);
			this.getListrencanalembur().down('#btnxpdf').setDisabled(true);
			this.getListrencanalembur().down('#btnprint').setDisabled(true);
			this.getListrencanalembur().getStore().removeAll();
		}
	},
		
	rLemburBedit: function(editor,e){
		var getListsplembur = this.getListsplembur();
		var sel = getListsplembur.getSelectionModel().getSelection()[0];
		var getListrencanalembur = this.getListrencanalembur();
		
		if(sel.data.TGLSETUJU != null || sel.data.TGLPERSONALIA != null){
			return false;
		}
		else if (user_nik == sel.data.NIKPERSONALIA) {
			return false;
		}
		else if(user_nik == sel.data.NIKUSUL) {
			getListrencanalembur.rowEditing.getEditor().items.items[1].setReadOnly(false);
			getListrencanalembur.rowEditing.getEditor().items.items[2].setReadOnly(false);
			getListrencanalembur.rowEditing.getEditor().items.items[3].setReadOnly(false);
			getListrencanalembur.rowEditing.getEditor().items.items[4].setReadOnly(false);
			getListrencanalembur.rowEditing.getEditor().items.items[5].setReadOnly(false);
			getListrencanalembur.rowEditing.getEditor().items.items[6].setReadOnly(false);		
			getListrencanalembur.rowEditing.getEditor().items.items[7].setReadOnly(false);			
			return true;
		}
	},
	
	
	updateListsplembur: function(me, record, item, index, e){
		var getSPLEMBUR		= this.getSPLEMBUR();
		var getListsplembur	= this.getListsplembur();
		var getV_splembur_form= this.getV_splembur_form(),
			form			= getV_splembur_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_splembur_form.down('#NOLEMBUR_field').setReadOnly(true);	
		getV_splembur_form.loadRecord(record);
		
		if (record.data.TGLSETUJU != null || record.data.TGLPERSONALIA != null){			
			getV_splembur_form.down('#NIKUSUL_field').setReadOnly(true);
			getV_splembur_form.down('#NIKSETUJU_field').setReadOnly(true);
			getV_splembur_form.down('#NIKPERSONALIA_field').setReadOnly(true);
			getV_splembur_form.down('#TANGGAL_field').setReadOnly(true);
			getV_splembur_form.down('#TGLSETUJU_field').setReadOnly(true);
			getV_splembur_form.down('#TGLPERSONALIA_field').setReadOnly(true);
		}
		
		if(getV_splembur_form.down('#NIKSETUJU_field').getValue() == user_nik)
		{
			getV_splembur_form.down('#NIKUSUL_field').setReadOnly(true);
			getV_splembur_form.down('#NIKSETUJU_field').setReadOnly(true);
			getV_splembur_form.down('#NIKPERSONALIA_field').setReadOnly(true);
			getV_splembur_form.down('#TANGGAL_field').setReadOnly(true);
			getV_splembur_form.down('#TGLSETUJU_field').setReadOnly(false);
		}
		else if(getV_splembur_form.down('#NIKPERSONALIA_field').getValue() == user_nik)
		{
			getV_splembur_form.down('#NIKUSUL_field').setReadOnly(true);
			getV_splembur_form.down('#NIKSETUJU_field').setReadOnly(true);
			getV_splembur_form.down('#NIKPERSONALIA_field').setReadOnly(true);
			getV_splembur_form.down('#TANGGAL_field').setReadOnly(true);
		
			if (record.data.TGLSETUJU != null){
				getV_splembur_form.down('#TGLPERSONALIA_field').setReadOnly(false);
			}
			else
				getV_splembur_form.down('#TGLPERSONALIA_field').setReadOnly(true);
		}
		else
		{
			getV_splembur_form.down('#TGLSETUJU_field').setReadOnly(true);
			getV_splembur_form.down('#TGLPERSONALIA_field').setReadOnly(true);
		}
		
		getListsplembur.setDisabled(true);
		getV_splembur_form.setDisabled(false);
		getSPLEMBUR.setActiveTab(getV_splembur_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListsplembur().getStore();
		var selection = this.getListsplembur().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NOLEMBUR" = "'+selection.data.NOLEMBUR+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListsplembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_splembur/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListsplembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_splembur/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/splembur.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListsplembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_splembur/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/splembur.html','splembur_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_splembur_form: function(){
		var getSPLEMBUR		= this.getSPLEMBUR();
		var getListsplembur 	= this.getListsplembur();
		var getV_splembur_form= this.getV_splembur_form(),
			form			= getV_splembur_form.getForm(),
			values			= getV_splembur_form.getValues();
		var store 			= this.getStore('s_splembur');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_splembur/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NOLEMBUR') === values.NOLEMBUR) {
										return true;
									}
									return false;
								}
							);
							/* getListsplembur.getView().select(recordIndex); */
							getListsplembur.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_splembur_form.setDisabled(true);
					getListsplembur.setDisabled(false);
					getSPLEMBUR.setActiveTab(getListsplembur);
				}
			});
		}
	},
	
	createV_splembur_form: function(){
		var getSPLEMBUR		= this.getSPLEMBUR();
		var getListsplembur 	= this.getListsplembur();
		var getV_splembur_form= this.getV_splembur_form(),
			form			= getV_splembur_form.getForm(),
			values			= getV_splembur_form.getValues();
		var store 			= this.getStore('s_splembur');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_splembur/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_splembur_form.setDisabled(true);
					getListsplembur.setDisabled(false);
					getSPLEMBUR.setActiveTab(getListsplembur);
				}
			});
		}
	},
	
	cancelV_splembur_form: function(){
		var getSPLEMBUR		= this.getSPLEMBUR();
		var getListsplembur	= this.getListsplembur();
		var getV_splembur_form= this.getV_splembur_form(),
			form			= getV_splembur_form.getForm();
			
		form.reset();
		getV_splembur_form.setDisabled(true);
		getListsplembur.setDisabled(false);
		getSPLEMBUR.setActiveTab(getListsplembur);
	}
	
});