Ext.define('YMPI.controller.UNITKERJA',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.UnitKerjaList', 'MASTER.JabatanList'],
	models: ['UnitKerja', 'Jabatan'],
	stores: ['UnitKerja', 'Jabatan'],
	
	requires: [],
	
	refs: [{
		ref: 'UnitKerjaList',
		selector: 'UnitKerjaList'
	},{
		ref: 'JabatanList',
		selector: 'JabatanList'
	}],


	init: function(){
		this.control({
			'UnitKerjaList': {
				'selectionchange': this.enableDeleteUnit
			},
			'UnitKerjaList button[action=create]': {
				click: this.createRecordUnit
			},
			'UnitKerjaList button[action=delete]': {
				click: this.deleteRecordUnit
			},
			'JabatanList': {
				afterrender: function(me, e){
					console.log('activate jabatan');
				},
				'selectionchange': this.enableDeleteJabatan
			},
			'JabatanList button[action=create]': {
				click: this.createRecordJabatan
			},
			'JabatanList button[action=delete]': {
				click: this.deleteRecordJabatan
			}
		});
	},
	
	enableDeleteUnit: function(dataview, selections){
		var getJabatanList 		= this.getJabatanList(),
			getJabatanStore 	= getJabatanList.getStore();
		var getUnitKerjaList 	= this.getUnitKerjaList();
		if(selections.length){
			var kodeunit = selections[0].data.KODEUNIT;
			var namaunit = selections[0].data.NAMAUNIT;
			
			getUnitKerjaList.down('#btndelete').setDisabled(!selections.length);
			getJabatanList.down('#btndelete').setDisabled(!selections.length);
			getJabatanList.down('#btnadd').setDisabled(!selections.length);
			getJabatanList.setTitle('Jabatan - ['+kodeunit+'] '+namaunit);
			
			/*jabStore.clearFilter(true);
			jabStore.filter("KODEUNIT", kodeunit);
			jabStore.load();*/
			getJabatanStore.load({
				params: {
					KODEUNIT: kodeunit
				}
			});
		}else{
			getJabatanList.setTitle('Jabatan');
			
			getUnitKerjaList.down('#btndelete').setDisabled(!selections.length);
			getJabatanList.down('#btndelete').setDisabled(!selections.length);
			getJabatanList.down('#btnadd').setDisabled(!selections.length);
			
			getJabatanStore.loadData([],false);
		}
	},
	
	createRecordUnit: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.UnitKerja');
		var grid 		= this.getUnitKerjaList();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
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
		//record = Ext.create('YMPI.model.Grade');
        //record.set(data);
		var r = Ext.ModelManager.create({
		    KODEUNIT	: '',
		    NAMAUNIT	: '',
		    P_KODEUNIT	: parent
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordUnit: function(dataview, selections){
		var getstore = this.getUnitKerjaList().getStore();
		var selection = this.getUnitKerjaList().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Kode Unit = \"'+selection.data.KODEUNIT+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	},
	
	createRecordJabatan: function(){
		var model			= Ext.ModelMgr.getModel('YMPI.model.Jabatan');
		var jabgrid 		= this.getJabatanList();
		var unitgrid 		= this.getUnitKerjaList();
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
	
	enableDeleteJabatan: function(dataview, selections){
		if(selections.length){
			this.getJabatanList().down('#btndelete').setDisabled(!selections.length);
		}
	},
	
	deleteRecordJabatan: function(dataview, selections){
		var getstore = this.getUnitKerjaList().getStore();
		var selection = this.getUnitKerjaList().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Kode Unit = \"'+selection.data.KODEUNIT+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	}
});