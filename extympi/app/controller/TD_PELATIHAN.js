Ext.define('YMPI.controller.TD_PELATIHAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_td_pelatihan','TRANSAKSI.v_td_pelatihan_form'],
	models: ['m_td_pelatihan'],
	stores: ['s_td_pelatihan','YMPI.store.s_karyawan','YMPI.store.s_td_training'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtd_pelatihan',
		selector: 'Listtd_pelatihan'
	}, {
		ref: 'v_td_pelatihan_form',
		selector: 'v_td_pelatihan_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_td_pelatihan_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_td_pelatihan_form #create'
	}, {
		ref: 'TD_PELATIHAN',
		selector: 'TD_PELATIHAN'
	}],


	init: function(){
		this.control({
			'TD_PELATIHAN': {
				'afterrender': this.td_pelatihanAfterRender
			},
			'Listtd_pelatihan': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListtd_pelatihan
			},
			'Listtd_pelatihan button[action=create]': {
				click: this.createRecord
			},
			'Listtd_pelatihan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtd_pelatihan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtd_pelatihan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtd_pelatihan button[action=print]': {
				click: this.printRecords
			},
			'v_td_pelatihan_form button[action=save]': {
				click: this.saveV_td_pelatihan_form
			},
			'v_td_pelatihan_form button[action=create]': {
				click: this.saveV_td_pelatihan_form
			},
			'v_td_pelatihan_form button[action=cancel]': {
				click: this.cancelV_td_pelatihan_form
			}
		});
	},
	
	td_pelatihanAfterRender: function(){
		var td_pelatihanStore = this.getListtd_pelatihan().getStore();
		td_pelatihanStore.load();
	},
	
	createRecord: function(){
		var getListtd_pelatihan	= this.getListtd_pelatihan();
		var getV_td_pelatihan_form= this.getV_td_pelatihan_form(),
			form			= getV_td_pelatihan_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListtd_pelatihan.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_td_pelatihan_form.down('#TDPELATIHAN_ID_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_td_pelatihan_form.setDisabled(false);
		
		this.getTD_PELATIHAN().setActiveTab(getV_td_pelatihan_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListtd_pelatihan().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListtd_pelatihan: function(me, record, item, index, e){
		var getTD_PELATIHAN		= this.getTD_PELATIHAN();
		var getListtd_pelatihan	= this.getListtd_pelatihan();
		var getV_td_pelatihan_form= this.getV_td_pelatihan_form(),
			form			= getV_td_pelatihan_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_td_pelatihan_form.down('#TDPELATIHAN_ID_field').setReadOnly(true);		
		getV_td_pelatihan_form.loadRecord(record);

		var str_rencana = record.data.TDRENCANA_TANGGAL;
		if (str_rencana) {
			var arrtgl_rencana = str_rencana.split(",");
			/*for (var i = 0; i < arrtgl_rencana.length; i++) {
				getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_PLAN_field').setDates(new Date(arrtgl_rencana[i]));
			};*/
			arrtgl_rencana.forEach(function(rec_rencana){
				console.log(rec_rencana);
				getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_PLAN_field').setDates(new Date(rec_rencana));
			});
		};

		var str_realisasi = record.data.TDREALISASI_TANGGAL;
		if (str_realisasi) {
			var arrtgl_realisasi = str_realisasi.split(",");
			/*for (var j = 0; j < arrtgl_realisasi.length; j++) {
				getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').setDates(new Date(arrtgl_realisasi[j]));
			};*/
			arrtgl_realisasi.forEach(function(rec_realisasi){
				getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_PLAN_field').setDates(new Date(rec_realisasi));
			});
		};
		
		getListtd_pelatihan.setDisabled(true);
		getV_td_pelatihan_form.setDisabled(false);
		getTD_PELATIHAN.setActiveTab(getV_td_pelatihan_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtd_pelatihan().getStore();
		var selection = this.getListtd_pelatihan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "TDPELATIHAN_ID" = "'+selection.data.TDPELATIHAN_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtd_pelatihan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_pelatihan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtd_pelatihan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_pelatihan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/td_pelatihan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtd_pelatihan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_pelatihan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/td_pelatihan.html','td_pelatihan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_td_pelatihan_form: function(){
		var getTD_PELATIHAN		= this.getTD_PELATIHAN();
		var getListtd_pelatihan 	= this.getListtd_pelatihan();
		var getV_td_pelatihan_form= this.getV_td_pelatihan_form(),
			form			= getV_td_pelatihan_form.getForm(),
			values			= getV_td_pelatihan_form.getValues();
		var store 			= this.getStore('s_td_pelatihan');
		// console.log(getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_PLAN_field').getSelectedDates());
		// console.log(getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').getSelectedDates());
		values.TDPELATIHAN_DATE_PLAN = getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_PLAN_field').getSelectedDates();
		values.TDPELATIHAN_DATE_AKTUAL = getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').getSelectedDates();
		// console.log(values);
		// 
		// var now = new Date();
		// getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').reset();
		// getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').setDates(new Date());
		// getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').setDates(new Date('2015-05-23'));
		// getV_td_pelatihan_form.down('#TDPELATIHAN_DATE_AKTUAL_field').setDates(new Date('2015-05-21'));
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_td_pelatihan/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('TDPELATIHAN_ID') === values.TDPELATIHAN_ID) {
										return true;
									}
									return false;
								}
							);
							getListtd_pelatihan.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_td_pelatihan_form.setDisabled(true);
					getListtd_pelatihan.setDisabled(false);
					getTD_PELATIHAN.setActiveTab(getListtd_pelatihan);
				}
			});
		} else {
			Ext.MessageBox.show({
				title: 'Warning',
				msg: 'Data belum lengkap!',
				buttons: Ext.MessageBox.OK,
				animEl: 'save',
				icon: Ext.MessageBox.WARNING
			});
		}
	},
	
	createV_td_pelatihan_form: function(){
		var getTD_PELATIHAN		= this.getTD_PELATIHAN();
		var getListtd_pelatihan 	= this.getListtd_pelatihan();
		var getV_td_pelatihan_form= this.getV_td_pelatihan_form(),
			form			= getV_td_pelatihan_form.getForm(),
			values			= getV_td_pelatihan_form.getValues();
		var store 			= this.getStore('s_td_pelatihan');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_td_pelatihan/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_td_pelatihan_form.setDisabled(true);
					getListtd_pelatihan.setDisabled(false);
					getTD_PELATIHAN.setActiveTab(getListtd_pelatihan);
				}
			});
		}
	},
	
	cancelV_td_pelatihan_form: function(){
		var getTD_PELATIHAN		= this.getTD_PELATIHAN();
		var getListtd_pelatihan	= this.getListtd_pelatihan();
		var getV_td_pelatihan_form= this.getV_td_pelatihan_form(),
			form			= getV_td_pelatihan_form.getForm();
			
		form.reset();
		getV_td_pelatihan_form.setDisabled(true);
		getListtd_pelatihan.setDisabled(false);
		getTD_PELATIHAN.setActiveTab(getListtd_pelatihan);
	}
	
});