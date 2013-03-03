Ext.define('YMPI.controller.UnitKerjaDanJabatan',{
	extend: 'Ext.app.Controller',
	views: ['dataMaster.UnitKerja', 'dataMaster.Jabatan'],
	models: ['UnitKerja', 'Jabatan'],
	stores: ['UnitKerja', 'Jabatan'],
	
	requires: [],
	
	refs: [{
		ref: 'unitKerjaGrid',
		selector: 'unitKerjaGrid'
	},{
		ref: 'unitKerjaModel',
		selector: 'unitKerjaModel'
	},{
		ref: 'jabatanGrid',
		selector: 'jabatanGrid'
	},{
		ref: 'jabatanModel',
		selector: 'jabatanModel'
	}],


	init: function(){
		this.control({
			'unitKerjaGrid': {
				'selectionchange': this.enableDeleteUnit
			},
			'unitKerjaGrid button[action=create]': {
				click: this.createRecordUnit
			},
			'unitKerjaGrid button[action=delete]': {
				click: this.deleteRecordUnit
			},
			'jabatanGrid': {
				afterrender: function(me, e){
					console.log('activate jabatan');
				},
				'selectionchange': this.enableDeleteJabatan
			},
			'jabatanGrid button[action=create]': {
				click: this.createRecordJabatan
			},
			'jabatanGrid button[action=delete]': {
				click: this.deleteRecordJabatan
			}
		});
	},
	
	createRecordUnit: function(){
		var grid = this.getUnitKerjaGrid();
		var selections = grid.getSelectionModel().getSelection();
		var index = 0;
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
		}, this.getUnitKerjaModel());
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	enableDeleteUnit: function(dataview, selections){
		var jabPanel = this.getJabatanGrid();
		if(selections.length){
			var kodeunit = selections[0].data.KODEUNIT;
			var namaunit = selections[0].data.NAMAUNIT;
			this.getUnitKerjaGrid().down('#btndelete').setDisabled(!selections.length);
			
			jabPanel.setTitle('Jabatan - ['+kodeunit+'] '+namaunit);
			
			var jabStore = this.getJabatanGrid().getStore();
			/*jabStore.clearFilter(true);
			jabStore.filter("KODEUNIT", kodeunit);
			jabStore.load();*/
			jabStore.load({
				params: {
					KODEUNIT: kodeunit
				}
			});
		}else{
			jabPanel.setTitle('Jabatan');
		}
	},
	
	deleteRecordUnit: function(dataview, selections){
		var getstore = this.getUnitKerjaGrid().getStore();
		var selection = this.getUnitKerjaGrid().getSelectionModel().getSelection()[0];
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
		var jabgrid = this.getJabatanGrid();
		var unitgrid = this.getUnitKerjaGrid();
		var unitselections = unitgrid.getSelectionModel().getSelection();
		var index = 0;
		
		var r = Ext.ModelManager.create({
			ID		: 0,
		    KODEJAB	: '',
		    NAMAJAB	: '',
		    KODEUNIT: unitselections[0].data.KODEUNIT
		}, this.getJabatanModel());
		jabgrid.getStore().insert(index, r);
		jabgrid.rowEditing.startEdit(index,0);
	},
	
	enableDeleteJabatan: function(dataview, selections){
		if(selections.length){
			this.getJabatanGrid().down('#btndelete').setDisabled(!selections.length);
		}
	},
	
	deleteRecordJabatan: function(dataview, selections){
		var getstore = this.getUnitKerjaGrid().getStore();
		var selection = this.getUnitKerjaGrid().getSelectionModel().getSelection()[0];
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