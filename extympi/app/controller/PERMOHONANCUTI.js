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
				'beforeedit': this.cekLogin,
				'validateedit': this.rinciancutiValidate,
				'edit': this.rinciancutiAfterEdit
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
		getV_permohonancuti_form.down('#NIKATASANC2_field').setReadOnly(false);
		getV_permohonancuti_form.down('#NIKHR_field').setReadOnly(true);
		getV_permohonancuti_form.down('#NOCUTI_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_permohonancuti_form.setDisabled(false);
		
		this.getPERMOHONANCUTI().setActiveTab(getV_permohonancuti_form);		
	},
	
	enableDelete: function(dataview, selections){
		//console.info(selections[0].data);
		var getListpermohonancuti = this.getListpermohonancuti();
		var getListrinciancuti = this.getListrinciancuti();
		
		/*var task = new Ext.util.DelayedTask(function(){
			getListrinciancuti.getSelectionModel().deselectAll();
		});
		task.delay(100);*/
		
		if (selections.length) {
			var select_spl = selections[0].data;
			
			//this.getListpermohonancuti().down('#btndelete').setDisabled(!selections.length);
			
			/* v_rinciancuti */
			if (select_spl.NOCUTI != null || select_spl.NOCUTI != '') {
				//getListrinciancuti.down('#btncreate').setDisabled(false);
				//this.getListrinciancuti().down('#btndelete').setDisabled(false);
				getListrinciancuti.down('#btnxexcel').setDisabled(false);
				getListrinciancuti.down('#btnxpdf').setDisabled(false);
				getListrinciancuti.down('#btnprint').setDisabled(false);
				getListrinciancuti.getStore().load({
					params: {
						NOCUTI: select_spl.NOCUTI
					}
				});
				console.info('Dipilih dari SPL : '+select_spl.NOCUTI);
				
				/**
				 * 1. Check STATUSCUTI dari master yang terpilih
				 * 1.a. Jika = 'C' ==> master(permohonancuti) Tidak Boleh Update/Delete dan detail(rinciancuti) Tidak Boleh Create/Update/Delete
				 * 1.b. Jika = 'T', maka:
				 * >> NIK-DiTetapkan ==> master Tidak Boleh di-Edit, dan detail masih Boleh di-Edit ke 'C'
				 * >> Selain NIK-DiTetapkan ==> master Tidak Boleh di-Edit dan detail Tidak Boleh Create/Update/Delete
				 * 1.c. Jika = 'S', maka:
				 * >> NIK-DiTetapkan ==> master Boleh di-Edit ke 'T' / 'C' dan detail Boleh di-Edit ke 'C' / 'T'
				 * >> NIK-DiSetujui / NIK-Pemohon ==> master Tidak Boleh di-Edit dan detail Tidak Boleh Create/Update/Delete
				 * 1.d. Jika = 'A', maka:
				 * >> NIK-DiTetapkan ==> master Tidak Boleh di-Edit (karena belom = 'S') dan detail Tidak Boleh Create/Update/Delete
			 	 * >> NIK-DiSetujui ==> master Boleh di-Edit Hanya ke 'S' dan detail Tidak Boleh Create/Update/Delete
				 * >> NIK-Pemohon ==> master Boleh Create/Update/Delete dan detail Boleh Create/Update/Delete
				 *
				 * 2. Siapapun Boleh jadi Pemohon, jadi di master pasti Boleh Create
				 */
				if (select_spl.STATUSCUTI == 'A') {
					if (user_nik == select_spl.NIKATASAN1) {
						/* Setting master (permohonancuti) */
						getListpermohonancuti.down('#btndelete').setDisabled(false);
						
						/* Setting detail (rinciancuti) */
						getListrinciancuti.down('#btncreate').setDisabled(false);
						//getListrinciancuti.down('#btndelete').setDisabled(true);
						getListrinciancuti.rowEditing.disabled = false;
					}else{
						/* Setting master (permohonancuti) */
						getListpermohonancuti.down('#btndelete').setDisabled(true);
						
						/* Setting detail (rinciancuti) */
						getListrinciancuti.down('#btncreate').setDisabled(true);
						getListrinciancuti.rowEditing.disabled = true;
					}
					
				}else{
					getListpermohonancuti.down('#btndelete').setDisabled(true);
					
					/* Setting detail (rinciancuti) */
					getListrinciancuti.down('#btncreate').setDisabled(true);
					getListrinciancuti.down('#btndelete').setDisabled(true);
					
					if (select_spl.STATUSCUTI == 'C') {
						/* detail tidak boleh di-Edit */
						getListrinciancuti.rowEditing.disabled = true;
					}else if (select_spl.STATUSCUTI == 'T') {
						/* detail tidak boleh di-Edit */
						if (user_nik == select_spl.NIKHR) {
							getListrinciancuti.rowEditing.disabled = false;
						}else{
							getListrinciancuti.rowEditing.disabled = true;
						}
					}else if (select_spl.STATUSCUTI == 'S') {
						if (user_nik == select_spl.NIKHR) {
							/* NIK-DiTetapkan Boleh Edit detail ke 'T' / 'C' */
							getListrinciancuti.rowEditing.disabled = false;
						}else{
							getListrinciancuti.rowEditing.disabled = true;
						}
					}
				}
			}else{
				//getListrinciancuti.down('#btncreate').setDisabled(true);
				//this.getListrinciancuti().down('#btndelete').setDisabled(true);
				getListrinciancuti.down('#btnxexcel').setDisabled(true);
				getListrinciancuti.down('#btnxpdf').setDisabled(true);
				getListrinciancuti.down('#btnprint').setDisabled(true);
			}
			
			
			
			
			
			
			
			
			
			/*
			// * Jika user-login (user_nik) = NIK-DiSetujui (NIKATASANC2), maka
			// * >> Tidak Boleh Create rincian-cuti DAN masih Boleh Edit
			// * Jika user-login (user_nik) = NIK-DiTetapkan (NIKHR), maka
			// * >> Tidak Boleh Create rincian-cuti DAN Tidak Boleh Edit
			if(select_spl.NIKATASAN2 == user_nik)
			{
				// user-login = NIK-DiSetujui 
				this.getListrinciancuti().down('#btncreate').setDisabled(true);
				//this.getListrinciancuti().down('#btndelete').setDisabled(true);
			}
			else if(select_spl.NIKHR == user_nik)
			{
				// user-login = NIK-DiTetapkan 
				this.getListrinciancuti().down('#btncreate').setDisabled(true);
				//this.getListrinciancuti().down('#btndelete').setDisabled(true);
				this.getListrinciancuti().rowEditing.disabled = true;
			}
			
			// * Jika user-login = NIK-Pemohon DAN master yang dipilih ber-Status = 'A', maka:
			// * >> Boleh Delete master yang dipilih
			// * Selain itu Tidak Boleh Delete master
			if(select_spl.NIKATASAN1 == user_nik && select_spl.STATUSCUTI == 'A')
			{
				this.getListpermohonancuti().down('#btndelete').setDisabled(false);
			}
			else
			{
				this.getListpermohonancuti().down('#btndelete').setDisabled(true);
			}
			
			// * Jika master yang dipilih ber-Status = 'S' / 'T' / 'C' DAN user-login = NIK-DiSetujui / NIK-Pemohon, maka
			// * >> Tidak Boleh Create rincian-cuti
			if(select_spl.STATUSCUTI == 'S' || select_spl.STATUSCUTI == 'T' || select_spl.STATUSCUTI == 'C')
			{
				if(select_spl.NIKATASAN2 == user_nik || select_spl.NIKATASAN1 == user_nik)
				{
					this.getListrinciancuti().down('#btncreate').setDisabled(true);
					//this.getListrinciancuti().down('#btndelete').setDisabled(true);
				}
			}
			*/
		}else{
			this.getListpermohonancuti().down('#btndelete').setDisabled(!selections.length);
			this.getListrinciancuti().down('#btncreate').setDisabled(true);
			//this.getListrinciancuti().down('#btndelete').setDisabled(true);
			this.getListrinciancuti().down('#btnxexcel').setDisabled(true);
			this.getListrinciancuti().down('#btnxpdf').setDisabled(true);
			this.getListrinciancuti().down('#btnprint').setDisabled(true);
			this.getListrinciancuti().getStore().removeAll();
		}
	},
	
	updateListpermohonancuti: function(me, record, item, index, e){
		var getPERMOHONANCUTI		= this.getPERMOHONANCUTI();
		var getListpermohonancuti	= this.getListpermohonancuti();
		var getV_permohonancuti_form= this.getV_permohonancuti_form(),
			form					= getV_permohonancuti_form.getForm();
		var getListrinciancuti		= this.getListrinciancuti(),
			rinciancutiStore		= getListrinciancuti.getStore();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/**
		 * 1. Jika record.data.STATUSCUTI = 'C' / 'T' ==> Tidak Boleh masuk Form Update
		 * 2. Jika record.data.STATUSCUTI = 'S', maka:
		 * >> NIK-DiTetapkan ==> Boleh Masuk ke Form Update (syarat: rinciancuti ada records) untuk update dari 'S' ke 'C'/'T'
		 * >> Selain NIK-DiTetapkan ==> Tidak Boleh masuk ke Form Update
		 * 3. Jika record.data.STATUSCUTI = 'A', maka:
		 * >> NIK-DiTetapkan ==> Tidak Boleh masuk ke Form Update
		 * >> NIK-DiSetujui ==> Boleh masuk ke Form Update (syarat: rinciancuti ada records) untuk update dari 'A' ke 'S'
		 * >> NIK-Pemohon ==> Tidak Boleh masuk ke Form Update, yang bisa dilakukan Delete terlebih
		 * >>>> dahulu master(permohonancuti) kemudian create ulang
		 */
		if (record.data.STATUSCUTI != 'C' && record.data.STATUSCUTI != 'T') {
			getSaveBtnForm.setDisabled(false);
			getCreateBtnForm.setDisabled(true);
			getV_permohonancuti_form.down('#NOCUTI_field').setReadOnly(true);		
			getV_permohonancuti_form.loadRecord(record);
			
			if (record.data.STATUSCUTI == 'S') {
				if (user_nik == record.data.NIKHR && rinciancutiStore.getCount() > 0) {
					//Jika user_nik = NIK-DiTetapkan
					getV_permohonancuti_form.down('#NIKATASANC2_field').setReadOnly(true);
					getV_permohonancuti_form.down('#NIKHR_field').setReadOnly(true);
					
					if(getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "S"){
						getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(false);
					}else if(getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "T"){
						getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(false);
					}else if(getV_permohonancuti_form.down('#STATUSCUTI_field').getValue() == "C"){
						getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(true);
					}
					
					getListpermohonancuti.setDisabled(true);
					getV_permohonancuti_form.setDisabled(false);
					getPERMOHONANCUTI.setActiveTab(getV_permohonancuti_form);
				}
			}else{
				if (user_nik == record.data.NIKATASAN2 && rinciancutiStore.getCount() > 0) {
					//Jika user_nik = NIK-DiSetujui ==> untuk update STATUSCUTI dari 'A' ke 'S'
					getV_permohonancuti_form.down('#STATUSCUTI_field').setReadOnly(false);
					getV_permohonancuti_form.down('#NIKATASANC2_field').setReadOnly(true);
					getV_permohonancuti_form.down('#NIKHR_field').setReadOnly(true);
					
					getListpermohonancuti.setDisabled(true);
					getV_permohonancuti_form.setDisabled(false);
					getPERMOHONANCUTI.setActiveTab(getV_permohonancuti_form);
				}
			}
			
		}
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
		
	cekLogin: function(editor,e){
		var getListpermohonancuti = this.getListpermohonancuti();
		var sel = getListpermohonancuti.getSelectionModel().getSelection()[0];
		var getListrinciancuti = this.getListrinciancuti();
		console.info(sel.data.NIKHR);
		
		/**
		 * 1. Jika e.record.data.STATUSCUTI = 'C' ==> rinciancuti Tidak Boleh di-Update
		 * 2. Jika e.record.data.STATUSCUTI = 'T', maka:
		 * >> NIK-DiTetapkan ==> Boleh Update ke 'C'
		 * >> Selain NIK-DiTetapkan ==> Tidak Boleh Update
		 * 3. Jika e.record.data.STATUSCUTI = 'S', maka:
		 * >> NIK-DiTetapkan ==> Boleh Update ke 'C'
		 * >> Selain NIK-DiTetapkan ==> Tidak Boleh Update
		 * 4. Jika e.record.data.STATUSCUTI = 'A', maka:
		 * >> NIK-Pemohon ==> Boleh Update
		 * >> Selain NIK-Pemohon ==> Tidak Boleh Update
		 */
		if (e.record.data.STATUSCUTI != 'C') {
			if (e.record.data.STATUSCUTI == 'T' || e.record.data.STATUSCUTI == 'S') {
				if (user_nik == sel.data.NIKHR) {
					getListrinciancuti.rowEditing.getEditor().items.items[2].setReadOnly(true);
					//getListrinciancuti.rowEditing.getEditor().items.items[3].setReadOnly(true);
					//getListrinciancuti.rowEditing.getEditor().items.items[5].setReadOnly(true);
					//getListrinciancuti.rowEditing.getEditor().items.items[6].setReadOnly(true);
					getListrinciancuti.rowEditing.getEditor().items.items[9].setReadOnly(false);
					
					/*if(e.record.data.STATUSCUTI == 'C'){
						getListrinciancuti.rowEditing.getEditor().items.items[8].setDisabled(true);
					}else if(e.record.data.STATUSCUTI == 'T'){
						getListrinciancuti.rowEditing.getEditor().items.items[8].setDisabled(false);
					}*/
					return true;
				}else{
					return false;
				}
			}else {
				//e.record.data.STATUSCUTI == 'A'
				if (user_nik == sel.data.NIKATASAN1) {
					getListrinciancuti.rowEditing.getEditor().items.items[2].setReadOnly(false);
					//getListrinciancuti.rowEditing.getEditor().items.items[3].setReadOnly(false);
					//getListrinciancuti.rowEditing.getEditor().items.items[5].setReadOnly(false);
					//getListrinciancuti.rowEditing.getEditor().items.items[6].setReadOnly(false);			
					getListrinciancuti.rowEditing.getEditor().items.items[9].setReadOnly(true);
					
					return true;
				}
				else if (user_nik == sel.data.NIKHR) {
					getListrinciancuti.rowEditing.getEditor().items.items[2].setReadOnly(true);
					//getListrinciancuti.rowEditing.getEditor().items.items[3].setReadOnly(true);
					//getListrinciancuti.rowEditing.getEditor().items.items[5].setReadOnly(true);
					//getListrinciancuti.rowEditing.getEditor().items.items[6].setReadOnly(true);
					getListrinciancuti.rowEditing.getEditor().items.items[9].setReadOnly(false);
					
					/*if(e.record.data.STATUSCUTI == 'C'){
						getListrinciancuti.rowEditing.getEditor().items.items[8].setDisabled(true);
					}else if(e.record.data.STATUSCUTI == 'T'){
						getListrinciancuti.rowEditing.getEditor().items.items[8].setDisabled(false);
					}*/
					return true;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		
		
		/*if(sel.data.NIKATASAN2 == user_nik)
		{
			return false;
		}
		else if(sel.data.NIKATASAN1 == user_nik && (sel.data.STATUSCUTI == 'S' || sel.data.STATUSCUTI == 'T' || sel.data.STATUSCUTI == 'C'))
		{
			return false;
		}
		else if(sel.data.NIKHR == user_nik)
		{
			getListrinciancuti.rowEditing.getEditor().items.items[2].setReadOnly(true);
			getListrinciancuti.rowEditing.getEditor().items.items[3].setReadOnly(true);
			getListrinciancuti.rowEditing.getEditor().items.items[5].setReadOnly(true);
			getListrinciancuti.rowEditing.getEditor().items.items[6].setReadOnly(true);
			getListrinciancuti.rowEditing.getEditor().items.items[8].setReadOnly(false);
			
			//var task = new Ext.util.DelayedTask(function(){
				if(e.record.data.STATUSCUTI == 'C')
				{
					getListrinciancuti.rowEditing.getEditor().items.items[8].setDisabled(true);
				}
				else if(e.record.data.STATUSCUTI == 'T')
				{
					getListrinciancuti.rowEditing.getEditor().items.items[8].setDisabled(false);
				}
			//});
			//task.delay(1500);
		}
		else if(sel.data.NIKATASAN1 == user_nik)
		{
			getListrinciancuti.rowEditing.getEditor().items.items[2].setReadOnly(false);
			getListrinciancuti.rowEditing.getEditor().items.items[3].setReadOnly(false);
			getListrinciancuti.rowEditing.getEditor().items.items[5].setReadOnly(false);
			getListrinciancuti.rowEditing.getEditor().items.items[6].setReadOnly(false);			
			getListrinciancuti.rowEditing.getEditor().items.items[8].setValue('A');
			getListrinciancuti.rowEditing.getEditor().items.items[8].setReadOnly(true);
		}*/
		
	},
	
	rinciancutiValidate: function(editor, e){
		var getListpermohonancuti 	= this.getListpermohonancuti(),
			sel 					= getListpermohonancuti.getSelectionModel().getSelection()[0];
		
		if(e.newValues.TGLMULAI > e.newValues.TGLSAMPAI){
			Ext.MessageBox.show({
				title: 'Tanggal',
				msg: 'Cek kembali TGLMULAI dan TGLSAMPAI!',
				buttons: Ext.MessageBox.OK,
				icon: Ext.MessageBox.WARNING
			});
			return false;
		}else{
			if (user_nik == sel.data.NIKATASAN1) {
				if (e.newValues.JENISABSEN == 'CT' && e.newValues.SISACUTI == 0) {
					return false;
				}else{
					return true;
				}
			}else if (user_nik == sel.data.NIKHR) {
				if (e.newValues.STATUSCUTI == 'C') {
					return true;
				}else{
					Ext.MessageBox.show({
						title: 'Status Cuti',
						msg: 'Status Cuti hanya boleh diganti dengan dibatalkan.',
						buttons: Ext.MessageBox.OK,
						icon: Ext.MessageBox.WARNING
					});
					return false;
				}
			}else{
				return false;
			}
			
		}
	},
	
	rinciancutiAfterEdit: function(editor, e){
		console.log('after edit');
		return false;
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
		var rincianStore	= this.getStore('s_rinciancuti');
			
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
			else if(values.NIKHR === user_nik && (values.STATUSCUTI == 'S' || values.STATUSCUTI == 'A'))
			{
				Ext.Msg.show({
					title: 'Status Cuti',
					msg: 'Status Cuti hanya boleh diubah menjadi \'T\' atau \'C\'',
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
					var result = Ext.decode(response.responseText);
					
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
									if (record.get('NOCUTI') === result.data.NOCUTI) {
										return true;
									}
									return false;
								}
							);
							/* getListkaryawan.getView().select(recordIndex); */
							getListpermohonancuti.getSelectionModel().select(newRecordIndex);
							
							
							/*var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NOCUTI') === values.NOCUTI) {
										Ext.Ajax.request({
											url: 'c_permohonancuti/setStatusCuti',
											params: {
												NOCUTI: values.NOCUTI,
												STATUSCUTI: values.STATUSCUTI
											}
										});
										return true;
									}
									return false;
								}
							);
							getListpermohonancuti.getSelectionModel().select(newRecordIndex);*/
							
							rincianStore.load({
								params: {
									NOCUTI: result.data.NOCUTI
								}
							});
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