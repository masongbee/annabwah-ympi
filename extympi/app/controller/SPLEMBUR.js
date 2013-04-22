Ext.define('YMPI.controller.SPLEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.lembur','TRANSAKSI.rencanalembur'],
	models: ['lembur','rencanalembur'],
	stores: ['lembur','rencanalembur'],
	
	//requires: ['YMPI.view.TRANSAKSI.lembur'],
	
	refs: [{
		ref: 'lembur',
		selector: 'lembur'
	},{
		ref: 'rencanalembur',
		selector: 'rencanalembur'
	}],
	
	init: function(){
		this.control({
			'lembur': {
				'afterrender': this.LoadStore
			},
			/*'lembur': {
				'selectionchange': this.enableDeleteUnit
			},
			'rencanalembur': {
				'selectionchange': this.enableDeleteUnit
			},*/
			'lembur button[action=create]': {
				click: this.createRecordGroup
			},
			'lembur button[action=delete]': {
				click: this.deleteRecordGroup
			},
			'rencanalembur button[action=create]': {
				click: this.createRecordRencanaLembur
			},
			'rencanalembur button[action=delete]': {
				click: this.deleteRecordRencanaLembur
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getLemburStore = this.getLembur().getStore();
		getLemburStore.load();
		var getRencanalemburStore = this.getRencanalembur().getStore();
		getRencanalemburStore.load();
	},
	
	/*enableDeleteUnit: function(dataview, selections){
		var getLembur 		= this.getLembur(),
			getLemburStore 	= getLembur.getStore();
		var getRencanalembur 	= this.getRencanalembur();
		if(selections.length){
			var kodeunit = selections[0].data.KODEUNIT;
			var tanggal = selections[0].data.TANGGAL;
			
			getRencanalembur.down('#btndelete').setDisabled(!selections.length);
			getLembur.down('#btndelete').setDisabled(!selections.length);
			getLembur.down('#btnadd').setDisabled(!selections.length);
			getLembur.setTitle('Lembur - ['+kodeunit+'] '+tanggal);
			
			getLemburStore.load({
				params: {
					KODEUNIT: kodeunit
				}
			});
		}else{
			getLembur.setTitle('Lembur');
			
			getLembur.down('#btndelete').setDisabled(!selections.length);
			getRencanalembur.down('#btndelete').setDisabled(!selections.length);
			getRencanalembur.down('#btnadd').setDisabled(!selections.length);
			
			getLemburStore.loadData([],false);
		}
	},*/
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.lembur');
		var grid 		= this.getLembur();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			NOLEMBUR	: '',
		    KODEUNIT	: '',
		    TANGGAL		: '',
		    KEPERLUAN	: '',
		    NIKUSUL	: '',
		    NIKSETUJU	: '',
		    NIKDIKETAHUI	: '',
		    NIKPERSONALIA	: '',
		    TGLSETUJU	: '',
		    TGLPERSONALIA	: '',
		    USERNAME	: ''
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordGroup: function(dataview, selections){
		var getLembur = this.getLembur(),
			getLemburStore = getLembur.getStore();
		var selection = this.getLembur().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.NOURUT+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getLembur.down('#btndelete').setDisabled(true);
			    	
			    	getLemburStore.remove(selection);
			    	getLemburStore.sync();
			    }
			});
			
		}
	},
	
	createRecordRencanaLembur: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.rencanalembur');
		var grid 		= this.getRencanalembur();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			NOLEMBUR	: '',
		    NOURUT	: '',
		    NIK		: '',
		    TJMASUK	: '',
		    TJKELUAR	: '',
		    ANTARJEMPUT	: '',
		    MAKAN	: ''
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordRencanaLembur: function(dataview, selections){
		var getRencanalembur = this.getRencanalembur(),
			getLemburStore = getRencanalembur.getStore();
		var selection = this.getRencanalembur().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.NOURUT+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getRencanalembur.down('#btndelete').setDisabled(true);
			    	
			    	getLemburStore.remove(selection);
			    	getLemburStore.sync();
			    }
			});
			
		}
	}
});