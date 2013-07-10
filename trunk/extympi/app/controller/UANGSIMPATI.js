Ext.define('YMPI.controller.UANGSIMPATI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_uangsimpati'],
	models: ['m_uangsimpati'],
	stores: ['s_uangsimpati'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listuangsimpati',
		selector: 'Listuangsimpati'
	}],


	init: function(){
		this.control({
			'Listuangsimpati': {
				'afterrender': this.uangsimpatiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listuangsimpati button[action=create]': {
				click: this.createRecord
			},
			'Listuangsimpati button[action=delete]': {
				click: this.deleteRecord
			},
			'Listuangsimpati button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listuangsimpati button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listuangsimpati button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	uangsimpatiAfterRender: function(){
		var uangsimpatiStore = this.getListuangsimpati().getStore();
		uangsimpatiStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_uangsimpati');
		var r = Ext.ModelManager.create({
		BULAN		: '',NIK		: '',JNSSIMPATI		: '',RPTSIMPATI		: '',KETERANGAN		: '',NIKATASAN1		: '',NIKATASAN2		: '',NIKATASAN3		: '',NIKPERSONALIA		: ''}, model);
		this.getListuangsimpati().getStore().insert(0, r);
		this.getListuangsimpati().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListuangsimpati().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListuangsimpati().getStore();
		var selection = this.getListuangsimpati().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: JNSSIMPATI = "'+selection.data.JNSSIMPATI+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListuangsimpati().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_uangsimpati/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListuangsimpati().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_uangsimpati/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/uangsimpati.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListuangsimpati().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_uangsimpati/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/uangsimpati.html','uangsimpati_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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