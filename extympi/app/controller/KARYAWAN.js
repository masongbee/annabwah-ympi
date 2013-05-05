Ext.define('YMPI.controller.KARYAWAN',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.KaryawanForm'
	        ,'MUTASI.KARUTAMA'
	        ,'MUTASI.KARKELUARGA'
	        ,'MUTASI.KARSKILL'
	        ,'MUTASI.KARRWYKERJA'
	        ,'MUTASI.KARRWYYMPI'
	        ,'MUTASI.KARRWYTRN'
	        ,'MUTASI.KARRWYSHT'
	        ,'MUTASI.KARHARGA'],
	models: ['Karyawan', 'Keluarga', 'Skill', 'RiwayatKerja', 'riwayatkerjaympi', 'riwayattraining', 'riwayatsehat', 'penghargaan'],
	stores: ['Karyawan', 'Keluarga', 'Skill', 'RiwayatKerja', 'riwayatkerjaympi', 'riwayattraining', 'riwayatsehat', 'penghargaan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'EastPanel',
		selector: 'KARYAWAN #east-region-container'
	}, {
		ref: 'KARUTAMA',
		selector: 'KARUTAMA'
	}, {
		ref: 'KaryawanForm',
		selector: 'KaryawanForm'
	}],


	init: function(){
		this.control({
			'KARYAWAN': {
				'afterrender': this.afterrenderKARYAWAN
			},
			'KARUTAMA button[action=create]': {
				click: this.createKARUTAMARecord
			},
			'KaryawanForm button[action=cancel]': {
				click: this.cancelKaryawanForm
			},
			'KaryawanForm button[action=create]': {
				click: this.createKaryawanForm
			}
		});
		/*this.control({
			'KaryawanList': {
				'selectionchange': this.enableDelete
			},
			'KaryawanList button[action=create]': {
				click: this.createRecord
			},
			'KaryawanList button[action=delete]': {
				click: this.deleteRecord
			},
			'KaryawanList button[action=xexcel]': {
				click: this.export2Excel
			},
			'KaryawanList button[action=print]': {
				click: this.printRecords
			},
			'KaryawanForm button[action=cancel]': {
				click: this.cancelKaryawanForm
			}
		});*/
	},
	
	afterrenderKARYAWAN: function(){
		var getEastPanel = this.getEastPanel();
		var getKaryawanForm = this.getKaryawanForm(),
			form			= getKaryawanForm.getForm();
		
		form.reset();
		getEastPanel.expand(true);
	},
	
	createKARUTAMARecord: function(){
		var getEastPanel = this.getEastPanel();
		var getKaryawanForm	= this.getKaryawanForm(),
			form			= getKaryawanForm.getForm();
		
		form.reset();
		getEastPanel.expand(true);
		/*var model		= Ext.ModelMgr.getModel('YMPI.model.Karyawan');
		var r = Ext.ModelManager.create({
		    NIK			: '00',
		    NAMAKAR		: '',
		    JENISKEL	: '',
		    TGLLAHIR	: '',
		    TMPLAHIR	: '',
		    TELEPON		: '',
		    AGAMA		: '',
		    ALAMAT		: '',
		    DESA		: '',
		    RT			: '',
		    RW			: '',
		    KECAMATAN	: '',
		    KOTA		: '',
		    KODEUNIT	: '',
		    KODEJAB		: '',
		    GRADE		: '',
		    TGLMASUK	: '',
		    BHSJEPANG	: ''
		}, model);
		this.getKaryawanList().getStore().insert(0, r);
		this.getKaryawanList().rowEditing.startEdit(0,0);*/
	},
	
	cancelKaryawanForm: function(){
		var getEastPanel = this.getEastPanel();
		var getKaryawanForm	= this.getKaryawanForm(),
			form			= getKaryawanForm.getForm();
		
		form.reset();
		getEastPanel.collapse('', true);
	},
	
	createKaryawanForm: function(){
		var getEastPanel = this.getEastPanel();
		var getKaryawanForm	= this.getKaryawanForm(),
			form			= getKaryawanForm.getForm(),
			values			= getKaryawanForm.getValues();
		var store 			= this.getStore('Karyawan');
		
		if (form.isValid()) {
			console.log(values);
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_karyawan/test',
				params: {data: jsonData},
				success: function(response){
					//window.location = ('./temp/'+response.responseText);
				}
			});
			//store.add(values);
			//store.sync();
			
			//form.reset();
			//getEastPanel.collapse('', true);
		}
		
	},
	
	enableDelete: function(dataview, selections){
		this.getKaryawanList().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getKaryawanList().getStore();
		var selection = this.getKaryawanList().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NIK = \"'+selection.data.NIK+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getKaryawanList().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_grade/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getKaryawanList().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_grade/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
			  	switch(result){
			  	case 1:
					win = window.open('./temp/grade.html','grade_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	}
	
});