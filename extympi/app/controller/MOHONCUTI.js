Ext.define('YMPI.controller.MOHONCUTI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_mohoncuti','TRANSAKSI.v_mohoncuti_form',
	'TRANSAKSI.v_rinciancuti'],
	models: ['m_mohoncuti','m_rinciancuti'],
	stores: ['s_mohoncuti','s_rinciancuti'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listmohoncuti',
		selector: 'Listmohoncuti'
	}, {
		ref: 'v_mohoncuti_form',
		selector: 'v_mohoncuti_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_mohoncuti_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_mohoncuti_form #create'
	}, {
		ref: 'MOHONCUTI',
		selector: 'MOHONCUTI #center'
	},{
		ref: 'Listrinciancuti',
		selector: 'Listrinciancuti'
	}],


	init: function(){
		this.control({
			'MOHONCUTI': {
				'afterrender': this.mohoncutiAfterRender
			},
			'Listmohoncuti': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListmohoncuti
			},
			'Listrinciancuti': {
				'beforeedit': this.cekLogin
			},
			'Listmohoncuti button[action=create]': {
				click: this.createRecord
			},
			'Listmohoncuti button[action=delete]': {
				click: this.deleteRecord
			},
			'Listmohoncuti button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listmohoncuti button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listmohoncuti button[action=print]': {
				click: this.printRecords
			},
			'v_mohoncuti_form button[action=save]': {
				click: this.saveV_mohoncuti_form
			},
			'v_mohoncuti_form button[action=create]': {
				click: this.saveV_mohoncuti_form
			},
			'v_mohoncuti_form button[action=cancel]': {
				click: this.cancelV_mohoncuti_form
			}
		});
	},
	
	mohoncutiAfterRender: function(){
		var mohoncutiStore = this.getListmohoncuti().getStore();
		
		mohoncutiStore.proxy.extraParams.nik = user_nik;
		mohoncutiStore.load();
	},
	
	createRecord: function(){
		var getListmohoncuti	= this.getListmohoncuti();
		var getV_mohoncuti_form= this.getV_mohoncuti_form(),
			form			= getV_mohoncuti_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListmohoncuti.setDisabled(true);
		
		Ext.Ajax.request({
			url: 'c_mohoncuti/getNIK',
			params: {
				NIK: user_nik
			},
			success: function(response){
				var msg = Ext.decode(response.responseText);
				//console.info(msg);
				if(msg.data != '')
				{
					getV_mohoncuti_form.down('#NIKATASANC1_field').setValue(msg.data[0].NAMA);
				}
			}
		});
        
		/* form-panel */
		form.reset();
		getV_mohoncuti_form.down('#NOCUTI_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_mohoncuti_form.setDisabled(false);
		
		this.getMOHONCUTI().setActiveTab(getV_mohoncuti_form);		
	},
	
	cekLogin: function(dataview,selections){	
		var sel = this.getListmohoncuti().getSelectionModel().getSelection()[0];
		console.info(sel.data.NIKHR);
		if(sel.data.NIKATASAN2 == user_nik)
			return false;
	},
	
	enableDelete: function(dataview, selections){
		//console.info(this.getListrinciancuti());	
		console.info(selections[0].data);
		
		if (selections.length) {
			var select_spl = selections[0].data;
			
			this.getListmohoncuti().down('#btndelete').setDisabled(!selections.length);
			
			/* v_rinciancuti */
			if (select_spl.NOCUTI != null) {
				this.getListrinciancuti().down('#btncreate').setDisabled(false);
				this.getListrinciancuti().down('#btndelete').setDisabled(false);
				this.getListrinciancuti().down('#btnxexcel').setDisabled(false);
				this.getListrinciancuti().down('#btnxpdf').setDisabled(false);
				this.getListrinciancuti().down('#btnprint').setDisabled(false);
				this.getListrinciancuti().getStore().load({
					params: {
						NOCUTI: select_spl.NOCUTI
					}
				});
				console.info('Dipilih dari SPL : '+select_spl.NOCUTI);
			}else{
				this.getListrinciancuti().down('#btncreate').setDisabled(true);
				this.getListrinciancuti().down('#btndelete').setDisabled(true);
				this.getListrinciancuti().down('#btnxexcel').setDisabled(true);
				this.getListrinciancuti().down('#btnxpdf').setDisabled(true);
				this.getListrinciancuti().down('#btnprint').setDisabled(true);
			}
		}else{
			this.getListmohoncuti().down('#btndelete').setDisabled(!selections.length);
			this.getListrinciancuti().down('#btncreate').setDisabled(true);
			this.getListrinciancuti().down('#btndelete').setDisabled(true);
			this.getListrinciancuti().down('#btnxexcel').setDisabled(true);
			this.getListrinciancuti().down('#btnxpdf').setDisabled(true);
			this.getListrinciancuti().down('#btnprint').setDisabled(true);
			this.getListrinciancuti().getStore().removeAll();
		}
		
		if(selections[0].data.NIKATASAN2 == user_nik)
		{			
			this.getListrinciancuti().down('#btncreate').setDisabled(true);
			this.getListrinciancuti().down('#btndelete').setDisabled(true);
		}
		
		if(selections[0].data.NIKHR == user_nik)
		{			
			this.getListrinciancuti().down('#btncreate').setDisabled(true);
			this.getListrinciancuti().down('#btndelete').setDisabled(true);
			this.getListrinciancuti().rowEditing.disabled = true;
		}
	},
	
	updateListmohoncuti: function(me, record, item, index, e){
		var getMOHONCUTI		= this.getMOHONCUTI();
		var getListmohoncuti	= this.getListmohoncuti();
		var getV_mohoncuti_form= this.getV_mohoncuti_form(),
			form			= getV_mohoncuti_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_mohoncuti_form.down('#NOCUTI_field').setReadOnly(true);
		
		getV_mohoncuti_form.loadRecord(record);
		
		if(getV_mohoncuti_form.down('#NIKATASANC1_field').getValue() == user_nik)
		{
			if(getV_mohoncuti_form.down('#STATUSCUTI_field').getValue() == "A")
				getV_mohoncuti_form.down('#STATUSCUTI_field').setReadOnly(true);
		}
		
		if(getV_mohoncuti_form.down('#NIKATASANC2_field').getValue() == user_nik)
		{
			if(getV_mohoncuti_form.down('#STATUSCUTI_field').getValue() == "T" || getV_mohoncuti_form.down('#STATUSCUTI_field').getValue() == "C")
			{
				getV_mohoncuti_form.down('#STATUSCUTI_field').setReadOnly(true);
			}
			else
				getV_mohoncuti_form.down('#STATUSCUTI_field').setReadOnly(false);
		}
		
		if(getV_mohoncuti_form.down('#NIKHR_field').getValue() == user_nik)
		{
			if(getV_mohoncuti_form.down('#STATUSCUTI_field').getValue() == "S")
				getV_mohoncuti_form.down('#STATUSCUTI_field').setReadOnly(false);
		}
		
		getListmohoncuti.setDisabled(true);
		getV_mohoncuti_form.setDisabled(false);
		getMOHONCUTI.setActiveTab(getV_mohoncuti_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListmohoncuti().getStore();
		var selection = this.getListmohoncuti().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NOCUTI" = "'+selection.data.NOCUTI+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListmohoncuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_mohoncuti/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListmohoncuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_mohoncuti/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/mohoncuti.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListmohoncuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_mohoncuti/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/mohoncuti.html','mohoncuti_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_mohoncuti_form: function(){
		var getMOHONCUTI		= this.getMOHONCUTI();
		var getListmohoncuti 	= this.getListmohoncuti();
		var getV_mohoncuti_form= this.getV_mohoncuti_form(),
			form			= getV_mohoncuti_form.getForm(),
			values			= getV_mohoncuti_form.getValues();
		var store 			= this.getStore('s_mohoncuti');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			if(values.NIKATASAN2 === user_nik && values.STATUSCUTI != 'S')
			{
				Ext.Msg.show({
					title: 'Status Cuti',
					msg: 'Status Cuti hanya boleh diubah menjadi \'S\'',
					minWidth: 200,
					modal: true,
					icon: Ext.Msg.INFO,
					buttons: Ext.Msg.OK
				});
				return false;
			}
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_mohoncuti/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NOCUTI') === values.NOCUTI) {
										return true;
									}
									return false;
								}
							);
							/* getListmohoncuti.getView().select(recordIndex); */
							getListmohoncuti.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_mohoncuti_form.setDisabled(true);
					getListmohoncuti.setDisabled(false);
					getMOHONCUTI.setActiveTab(getListmohoncuti);
				}
			});
		}
	},
	
	createV_mohoncuti_form: function(){
		var getMOHONCUTI		= this.getMOHONCUTI();
		var getListmohoncuti 	= this.getListmohoncuti();
		var getV_mohoncuti_form= this.getV_mohoncuti_form(),
			form			= getV_mohoncuti_form.getForm(),
			values			= getV_mohoncuti_form.getValues();
		var store 			= this.getStore('s_mohoncuti');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_mohoncuti/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_mohoncuti_form.setDisabled(true);
					getListmohoncuti.setDisabled(false);
					getMOHONCUTI.setActiveTab(getListmohoncuti);
				}
			});
		}
	},
	
	cancelV_mohoncuti_form: function(){
		var getMOHONCUTI		= this.getMOHONCUTI();
		var getListmohoncuti	= this.getListmohoncuti();
		var getV_mohoncuti_form= this.getV_mohoncuti_form(),
			form			= getV_mohoncuti_form.getForm();
			
		form.reset();
		getV_mohoncuti_form.setDisabled(true);
		getListmohoncuti.setDisabled(false);
		getMOHONCUTI.setActiveTab(getListmohoncuti);
	}
	
});