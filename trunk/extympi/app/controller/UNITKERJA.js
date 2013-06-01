Ext.define('YMPI.controller.UNITKERJA',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_unitkerja', 'MASTER.v_jabatan'],
	models: ['m_unitkerja', 'm_jabatan'],
	stores: ['s_unitkerja', 's_jabatan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listunitkerja',
		selector: 'Listunitkerja'
	}, {
		ref: 'Listjabatan',
		selector: 'Listjabatan'
	}],


	init: function(){
		this.control({
			'Listunitkerja': {
				'afterrender': this.afterrenderUnitkerja,
				'selectionchange': this.enableDeleteUnitkerja
			},
			'Listunitkerja button[action=create]': {
				click: this.createRecordUnitkerja
			},
			'Listunitkerja button[action=delete]': {
				click: this.deleteRecordUnitkerja
			},
			'Listunitkerja button[action=xexcel]': {
				click: this.export2ExcelUnitkerja
			},
			'Listunitkerja button[action=xpdf]': {
				click: this.export2PDFUnitkerja
			},
			'Listunitkerja button[action=print]': {
				click: this.printRecordsUnitkerja
			},
			'Listjabatan': {
				afterrender: function(me, e){
					console.log('activate jabatan');
				},
				'selectionchange': this.enableDeleteJabatan
			},
			'Listjabatan button[action=create]': {
				click: this.createRecordJabatan
			},
			'Listjabatan button[action=delete]': {
				click: this.deleteRecordJabatan
			}
		});
	},
	
	afterrenderUnitkerja: function(){
		var getStoreUnitkerja = this.getListunitkerja().getStore();
		getStoreUnitkerja.load();
	},
	
	createRecordUnitkerja: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_unitkerja');
		var grid 		= this.getListunitkerja();
		var selections 	= grid.getSelectionModel().getSelection();
		//console.log(selections.length);
		//console.log(selections[0]);
		var parent = '';
		/*if(!selections.length){
			//add new root
		}else{
			//add new by P_KODEUNIT
			index = selections[0].index;
			if(selections[0].data.P_KODEUNIT != '_'){
				parent = selections[0].data.P_KODEUNIT;
			}
		}*/
		//record = Ext.create('YMPI.model.UnitKerja');
        //record.set(data);
		var r = Ext.ModelManager.create({
		    KODEUNIT	: '',
		    NAMAUNIT	: '',
		    P_KODEUNIT	: parent
		}, model);
		grid.getStore().insert(0, r);
		grid.rowEditing.startEdit(0,0);
	},
	
	enableDeleteUnitkerja: function(dataview, selections){
		this.getListunitkerja().down('#btndelete').setDisabled(!selections.length);
		
		var getListjabatan 		= this.getListjabatan(),
			getStoreListjabatan 	= getListjabatan.getStore();
		var getListunitkerja 	= this.getListunitkerja();
		if(selections.length){
			var kodeunit = selections[0].data.KODEUNIT;
			var namaunit = selections[0].data.NAMAUNIT;
			
			getListunitkerja.down('#btndelete').setDisabled(!selections.length);
			getListjabatan.down('#btndelete').setDisabled(!selections.length);
			getListjabatan.down('#btncreate').setDisabled(!selections.length);
			getListjabatan.setTitle('Jabatan - ['+kodeunit+'] '+namaunit);
			
			/*jabStore.clearFilter(true);
			jabStore.filter("KODEUNIT", kodeunit);
			jabStore.load();*/
			getStoreListjabatan.load({
				params: {
					KODEUNIT: kodeunit
				}
			});
		}else{
			getListjabatan.setTitle('Jabatan');
			
			getListunitkerja.down('#btndelete').setDisabled(!selections.length);
			getListjabatan.down('#btndelete').setDisabled(!selections.length);
			getListjabatan.down('#btncreate').setDisabled(!selections.length);
			
			getStoreListjabatan.loadData([],false);
		}
	},
	
	deleteRecordUnitkerja: function(dataview, selections){
		var getstore = this.getListunitkerja().getStore();
		var selection = this.getListunitkerja().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Kode Unit = \"'+selection.data.KODEUNIT+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	},
	
	export2ExcelUnitkerja: function(){
		var getstore = this.getListunitkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_unitkerja/export2ExcelUnitkerja',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDFUnitkerja: function(){
		var getstore = this.getListunitkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_unitkerja/export2PDFUnitkerja',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/unitkerja.pdf', '_blank');
			}
		});
	},
	
	printRecordsUnitkerja: function(){
		var getstore = this.getListunitkerja().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_unitkerja/printRecordsUnitkerja',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/unitkerja.html','unitkerja_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	enableDeleteJabatan: function(dataview, selections){
		if(selections.length){
			this.getListjabatan().down('#btndelete').setDisabled(!selections.length);
		}
	},
	
	createRecordJabatan: function(){
		var model			= Ext.ModelMgr.getModel('YMPI.model.m_jabatan');
		var jabgrid 		= this.getListjabatan();
		var unitgrid 		= this.getListunitkerja();
		var unitselections 	= unitgrid.getSelectionModel().getSelection();
		var index 			= 0;
		
		var r = Ext.ModelManager.create({
		    KODEJAB	: '',
		    NAMAJAB	: '',
		    KODEUNIT: unitselections[0].data.KODEUNIT
		}, model);
		jabgrid.getStore().insert(index, r);
		jabgrid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordJabatan: function(dataview, selections){
		var getListjabatan 	= this.getListjabatan(),
			getStoreListjabatan	= getListjabatan.getStore();
		var selection = getListjabatan.getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Kode Jabatan = \"'+selection.data.KODEJAB+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getStoreListjabatan.remove(selection);
			    	getStoreListjabatan.sync();
			    }
			});
			
		}
	}
	
});