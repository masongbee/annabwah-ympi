Ext.define('YMPI.controller.PCICILAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_pcicilan'],
	models: ['m_pcicilan'],
	stores: ['s_pcicilan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpcicilan',
		selector: 'Listpcicilan'
	}],


	init: function(){
		this.control({
			'Listpcicilan': {
				'afterrender': this.pcicilanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpcicilan button[action=create]': {
				click: this.createRecord
			},
			'Listpcicilan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpcicilan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpcicilan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpcicilan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	pcicilanAfterRender: function(){
		var pcicilanStore = this.getListpcicilan().getStore();
		pcicilanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_pcicilan');
		var r = Ext.ModelManager.create({
		BULAN		: '',NOURUT		: '',NIK		: '',CICILANKE		: '',RPCICILAN		: '',LAMACICILAN		: '',KETERANGAN		: '',USERNAME		: ''}, model);
		this.getListpcicilan().getStore().insert(0, r);
		this.getListpcicilan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpcicilan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpcicilan().getStore();
		var selection = this.getListpcicilan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NOURUT = "'+selection.data.NOURUT+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListpcicilan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pcicilan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpcicilan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pcicilan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/pcicilan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpcicilan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pcicilan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/pcicilan.html','pcicilan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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