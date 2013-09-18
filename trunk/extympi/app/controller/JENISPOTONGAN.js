Ext.define('YMPI.controller.JENISPOTONGAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_jenispotongan'],
	models: ['m_jenispotongan'],
	stores: ['s_jenispotongan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listjenispotongan',
		selector: 'Listjenispotongan'
	}],


	init: function(){
		this.control({
			'Listjenispotongan': {
				'afterrender': this.jenispotonganAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listjenispotongan button[action=create]': {
				click: this.createRecord
			},
			'Listjenispotongan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listjenispotongan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listjenispotongan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listjenispotongan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	jenispotonganAfterRender: function(){
		var jenispotonganStore = this.getListjenispotongan().getStore();
		jenispotonganStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_jenispotongan');
		var r = Ext.ModelManager.create({
		KODEPOTONGAN		: '',NAMAPOTONGAN		: '',POSCETAK		: ''}, model);
		this.getListjenispotongan().getStore().insert(0, r);
		this.getListjenispotongan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListjenispotongan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListjenispotongan().getStore();
		var selection = this.getListjenispotongan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODEPOTONGAN = "'+selection.data.KODEPOTONGAN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListjenispotongan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenispotongan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListjenispotongan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenispotongan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/jenispotongan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListjenispotongan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenispotongan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/jenispotongan.html','jenispotongan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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