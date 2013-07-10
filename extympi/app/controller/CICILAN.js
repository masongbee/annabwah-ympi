Ext.define('YMPI.controller.CICILAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_cicilan'],
	models: ['m_cicilan'],
	stores: ['s_cicilan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listcicilan',
		selector: 'Listcicilan'
	}],


	init: function(){
		this.control({
			'Listcicilan': {
				'afterrender': this.cicilanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listcicilan button[action=create]': {
				click: this.createRecord
			},
			'Listcicilan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listcicilan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listcicilan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listcicilan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	cicilanAfterRender: function(){
		var cicilanStore = this.getListcicilan().getStore();
		cicilanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_cicilan');
		var r = Ext.ModelManager.create({
		NOCICILAN		: '',NIK		: '',TGLAMBIL		: '',RPPOKOK		: '',LAMACICILAN		: '',RPCICILAN		: '',RPCICILANAKHIR		: '',KEPERLUAN		: '',BULANMULAI		: '',LUNAS		: '',TGLLUNAS		: ''}, model);
		this.getListcicilan().getStore().insert(0, r);
		this.getListcicilan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListcicilan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListcicilan().getStore();
		var selection = this.getListcicilan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NOCICILAN = "'+selection.data.NOCICILAN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListcicilan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cicilan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListcicilan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cicilan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/cicilan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListcicilan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cicilan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/cicilan.html','cicilan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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