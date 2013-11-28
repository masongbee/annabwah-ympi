Ext.define('YMPI.controller.SHIFTJAMKERJA',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_shiftjamkerja','MASTER.v_shiftjamkerja_form'],
	models: ['m_shiftjamkerja'],
	stores: ['s_shiftjamkerja'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listshiftjamkerja',
		selector: 'Listshiftjamkerja'
	}, {
		ref: 'v_shiftjamkerja_form',
		selector: 'v_shiftjamkerja_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_shiftjamkerja_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_shiftjamkerja_form #create'
	}, {
		ref: 'SHIFTJAMKERJA',
		selector: 'SHIFTJAMKERJA'
	},{
		ref: 'Listshift',
		selector: 'Listshift'
	}],


	init: function(){
		this.control({
			'SHIFTJAMKERJA': {
				'afterrender': this.shiftjamkerjaAfterRender
			},
			'Listshiftjamkerja': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListshiftjamkerja
			},
			'Listshiftjamkerja button[action=create]': {
				click: this.createRecord
			},
			'Listshiftjamkerja button[action=delete]': {
				click: this.deleteRecord
			},
			'Listshiftjamkerja button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listshiftjamkerja button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listshiftjamkerja button[action=print]': {
				click: this.printRecords
			},
			'v_shiftjamkerja_form button[action=save]': {
				click: this.saveV_shiftjamkerja_form
			},
			'v_shiftjamkerja_form button[action=create]': {
				click: this.saveV_shiftjamkerja_form
			},
			'v_shiftjamkerja_form button[action=cancel]': {
				click: this.cancelV_shiftjamkerja_form
			}
		});
	},
	
	shiftjamkerjaAfterRender: function(){
		var shiftjamkerjaStore = this.getListshiftjamkerja().getStore();
		shiftjamkerjaStore.load();
	},
	
	createRecord: function(){
		var getListshiftjamkerja	= this.getListshiftjamkerja();
		var getV_shiftjamkerja_form= this.getV_shiftjamkerja_form(),
			form			= getV_shiftjamkerja_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListshiftjamkerja.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_shiftjamkerja_form.down('#NAMASHIFT_field').setReadOnly(false);getV_shiftjamkerja_form.down('#SHIFTKE_field').setReadOnly(false);getV_shiftjamkerja_form.down('#JENISHARI_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_shiftjamkerja_form.setDisabled(false);
		
		this.getSHIFTJAMKERJA().setActiveTab(getV_shiftjamkerja_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListshiftjamkerja().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListshiftjamkerja: function(me, record, item, index, e){
		var getSHIFTJAMKERJA		= this.getSHIFTJAMKERJA();
		var getListshiftjamkerja	= this.getListshiftjamkerja();
		var getV_shiftjamkerja_form= this.getV_shiftjamkerja_form(),
			form			= getV_shiftjamkerja_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_shiftjamkerja_form.down('#NAMASHIFT_field').setReadOnly(true);getV_shiftjamkerja_form.down('#SHIFTKE_field').setReadOnly(true);getV_shiftjamkerja_form.down('#JENISHARI_field').setReadOnly(true);		
		getV_shiftjamkerja_form.loadRecord(record);
		
		getListshiftjamkerja.setDisabled(true);
		getV_shiftjamkerja_form.setDisabled(false);
		getSHIFTJAMKERJA.setActiveTab(getV_shiftjamkerja_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListshiftjamkerja().getStore();
		var selection = this.getListshiftjamkerja().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NAMASHIFT" = "'+selection.data.NAMASHIFT+'","SHIFTKE" = "'+selection.data.SHIFTKE+'","JENISHARI" = "'+selection.data.JENISHARI+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListshiftjamkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_shiftjamkerja/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListshiftjamkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_shiftjamkerja/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/shiftjamkerja.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListshiftjamkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_shiftjamkerja/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/shiftjamkerja.html','shiftjamkerja_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_shiftjamkerja_form: function(){
		var getSHIFTJAMKERJA		= this.getSHIFTJAMKERJA();
		var getListshiftjamkerja 	= this.getListshiftjamkerja();
		var getV_shiftjamkerja_form= this.getV_shiftjamkerja_form(),
			form			= getV_shiftjamkerja_form.getForm(),
			values			= getV_shiftjamkerja_form.getValues();
		var store 			= this.getStore('s_shiftjamkerja');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_shiftjamkerja/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NAMASHIFT') === values.NAMASHIFT && record.get('SHIFTKE') === values.SHIFTKE && record.get('JENISHARI') === values.JENISHARI) {
										return true;
									}
									return false;
								}
							);
							/* getListshiftjamkerja.getView().select(recordIndex); */
							getListshiftjamkerja.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_shiftjamkerja_form.setDisabled(true);
					getListshiftjamkerja.setDisabled(false);
					getSHIFTJAMKERJA.setActiveTab(getListshiftjamkerja);
				}
			});
		}
	},
	
	createV_shiftjamkerja_form: function(){
		var getSHIFTJAMKERJA		= this.getSHIFTJAMKERJA();
		var getListshiftjamkerja 	= this.getListshiftjamkerja();
		var getV_shiftjamkerja_form= this.getV_shiftjamkerja_form(),
			form			= getV_shiftjamkerja_form.getForm(),
			values			= getV_shiftjamkerja_form.getValues();
		var store 			= this.getStore('s_shiftjamkerja');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_shiftjamkerja/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_shiftjamkerja_form.setDisabled(true);
					getListshiftjamkerja.setDisabled(false);
					getSHIFTJAMKERJA.setActiveTab(getListshiftjamkerja);
				}
			});
		}
	},
	
	cancelV_shiftjamkerja_form: function(){
		var getSHIFTJAMKERJA		= this.getSHIFTJAMKERJA();
		var getListshiftjamkerja	= this.getListshiftjamkerja();
		var getV_shiftjamkerja_form= this.getV_shiftjamkerja_form(),
			form			= getV_shiftjamkerja_form.getForm();
			
		form.reset();
		getV_shiftjamkerja_form.setDisabled(true);
		getListshiftjamkerja.setDisabled(false);
		getSHIFTJAMKERJA.setActiveTab(getListshiftjamkerja);
	}
	
});