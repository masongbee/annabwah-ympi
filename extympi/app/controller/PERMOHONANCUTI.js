Ext.define('YMPI.controller.PERMOHONANCUTI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_permohonancuti','TRANSAKSI.v_permohonancuti_form'],
	models: ['m_permohonancuti'],
	stores: ['s_permohonancuti'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpermohonancuti',
		selector: 'Listpermohonancuti'
	}, {
		ref: 'v_permohonancuti_form',
		selector: 'v_permohonancuti_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_permohonancuti_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_permohonancuti_form #create'
	}, {
		ref: 'PERMOHONANCUTI',
		selector: 'PERMOHONANCUTI #center'
	},{
		ref: 'Listrinciancuti',
		selector: 'Listrinciancuti'
	}],


	init: function(){
		this.control({
			'PERMOHONANCUTI': {
				'afterrender': this.permohonancutiAfterRender
			},
			'Listpermohonancuti': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListpermohonancuti
			},
			'Listrinciancuti': {
				'beforeedit': this.cekLogin
			},
			'Listpermohonancuti button[action=create]': {
				click: this.createRecord
			},
			'Listpermohonancuti button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpermohonancuti button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpermohonancuti button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpermohonancuti button[action=print]': {
				click: this.printRecords
			},
			'v_permohonancuti_form button[action=save]': {
				click: this.saveV_permohonancuti_form
			},
			'v_permohonancuti_form button[action=create]': {
				click: this.saveV_permohonancuti_form
			},
			'v_permohonancuti_form button[action=cancel]': {
				click: this.cancelV_permohonancuti_form
			}
		});
	},
	
	permohonancutiAfterRender: function(){
		var permohonancutiStore = this.getListpermohonancuti().getStore();
		permohonancutiStore.load();
	},
	
	createRecord: function(){
		var getListpermohonancuti	= this.getListpermohonancuti();
		var getV_permohonancuti_form= this.getV_permohonancuti_form(),
			form			= getV_permohonancuti_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListpermohonancuti.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_permohonancuti_form.down('#NIKATASANC1_field').setValue(user_nik);
		getV_permohonancuti_form.down('#NOCUTI_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_permohonancuti_form.setDisabled(false);
		
		this.getPERMOHONANCUTI().setActiveTab(getV_permohonancuti_form);		
	},
	
	enableDelete: function(dataview, selections){
		console.info(selections[0].data);
		
		if (selections.length) {
			var select_spl = selections[0].data;
			
			this.getListpermohonancuti().down('#btndelete').setDisabled(!selections.length);
			
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
			this.getListpermohonancuti().down('#btndelete').setDisabled(!selections.length);
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
	
	updateListpermohonancuti: function(me, record, item, index, e){
		var getPERMOHONANCUTI		= this.getPERMOHONANCUTI();
		var getListpermohonancuti	= this.getListpermohonancuti();
		var getV_permohonancuti_form= this.getV_permohonancuti_form(),
			form			= getV_permohonancuti_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_permohonancuti_form.down('#NOCUTI_field').setReadOnly(true);		
		getV_permohonancuti_form.loadRecord(record);
		
		if(getV_permohonancuti_form.down('#NIKATASANC1_field').getValue() == user_nik)
		{
			if(getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "A")
				getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(true);
		}
		
		if(getV_permohonancuti_form.down('#NIKATASANC2_field').getValue() == user_nik)
		{
			if(getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "T" || getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "C")
			{
				getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(true);
			}
			else
				getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(false);
		}
		
		if(getV_permohonancuti_form.down('#NIKHR_field').getValue() == user_nik)
		{
			if(getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "S")
				getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(false);
		}
		
		getListpermohonancuti.setDisabled(true);
		getV_permohonancuti_form.setDisabled(false);
		getPERMOHONANCUTI.setActiveTab(getV_permohonancuti_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpermohonancuti().getStore();
		var selection = this.getListpermohonancuti().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NOCUTI" = "'+selection.data.NOCUTI+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
		
	cekLogin: function(dataview,selections){
		var getListpermohonancuti = this.getListpermohonancuti();
		var sel = getListpermohonancuti.getSelectionModel().getSelection()[0];
		console.info(sel.data.NIKHR);
		if(sel.data.NIKATASAN2 == user_nik)
		{
			return false;
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListpermohonancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_permohonancuti/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpermohonancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_permohonancuti/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/permohonancuti.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpermohonancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_permohonancuti/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/permohonancuti.html','permohonancuti_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_permohonancuti_form: function(){
		var getPERMOHONANCUTI		= this.getPERMOHONANCUTI();
		var getListpermohonancuti 	= this.getListpermohonancuti();
		var getV_permohonancuti_form= this.getV_permohonancuti_form(),
			form			= getV_permohonancuti_form.getForm(),
			values			= getV_permohonancuti_form.getValues();
		var store 			= this.getStore('s_permohonancuti');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			if(values.NIKATASAN2 === user_nik && values.STATUSCUTI != 'S' && values.STATUSCUTI != 'A')
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
				url: 'c_permohonancuti/save',
				params: {data: jsonData},
				success: function(response){
					if(values.NIKATASAN2 === user_nik && values.STATUSCUTI === 'S')
					{
						Ext.Ajax.request({
							url: 'c_permohonancuti/uTglA2',
							params: {
								NOCUTI: values.NOCUTI,
								NIKATASAN2 : values.NIKATASAN2,
								TGLATASAN2 : new Date()
							}
						});
					}
					else if(values.NIKATASAN2 === user_nik && values.STATUSCUTI === 'A')
					{
						Ext.Ajax.request({
							url: 'c_permohonancuti/uTglA2',
							params: {
								NOCUTI: values.NOCUTI,
								NIKATASAN2 : values.NIKATASAN2,
								TGLATASAN2 : null
							}
						});
					}
					else if(values.NIKATASAN1 === user_nik)
					{
						Ext.Ajax.request({
							url: 'c_permohonancuti/uTglA1',
							params: {
								NOCUTI: values.NOCUTI,
								NIKATASAN1 : values.NIKATASAN1,
								TGLATASAN1 : new Date()
							}
						});
					}
					else if(values.NIKHR === user_nik)
					{
						Ext.Ajax.request({
							url: 'c_permohonancuti/uTglHR',
							params: {
								NOCUTI: values.NOCUTI,
								NIKHR : values.NIKHR,
								TGLHR : new Date()
							}
						});
					}
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
							/* getListpermohonancuti.getView().select(recordIndex); */
							getListpermohonancuti.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_permohonancuti_form.setDisabled(true);
					getListpermohonancuti.setDisabled(false);
					getPERMOHONANCUTI.setActiveTab(getListpermohonancuti);
				}
			});
		}
	},
	
	createV_permohonancuti_form: function(){
		var getPERMOHONANCUTI		= this.getPERMOHONANCUTI();
		var getListpermohonancuti 	= this.getListpermohonancuti();
		var getV_permohonancuti_form= this.getV_permohonancuti_form(),
			form			= getV_permohonancuti_form.getForm(),
			values			= getV_permohonancuti_form.getValues();
		var store 			= this.getStore('s_permohonancuti');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_permohonancuti/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_permohonancuti_form.setDisabled(true);
					getListpermohonancuti.setDisabled(false);
					getPERMOHONANCUTI.setActiveTab(getListpermohonancuti);
				}
			});
		}
	},
	
	cancelV_permohonancuti_form: function(){
		var getPERMOHONANCUTI		= this.getPERMOHONANCUTI();
		var getListpermohonancuti	= this.getListpermohonancuti();
		var getV_permohonancuti_form= this.getV_permohonancuti_form(),
			form			= getV_permohonancuti_form.getForm();
			
		form.reset();
		getV_permohonancuti_form.setDisabled(true);
		getListpermohonancuti.setDisabled(false);
		getPERMOHONANCUTI.setActiveTab(getListpermohonancuti);
	}
	
});