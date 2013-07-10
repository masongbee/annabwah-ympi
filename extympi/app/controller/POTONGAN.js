Ext.define('YMPI.controller.POTONGAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_potongan'],
	models: ['m_potongan'],
	stores: ['s_potongan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpotongan',
		selector: 'Listpotongan'
	}],


	init: function(){
		this.control({
			'Listpotongan': {
				'afterrender': this.potonganAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpotongan button[action=create]': {
				click: this.createRecord
			},
			'Listpotongan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpotongan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpotongan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpotongan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	potonganAfterRender: function(){
		var potonganStore = this.getListpotongan().getStore();
		potonganStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_potongan');
		var r = Ext.ModelManager.create({
		BULAN		: '',NOURUT		: '',GRADE		: '',JUMLAH		: '',KETERANGAN		: '',KODEJAB		: '',NIK		: '',USERNAME		: ''}, model);
		this.getListpotongan().getStore().insert(0, r);
		this.getListpotongan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpotongan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpotongan().getStore();
		var selection = this.getListpotongan().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListpotongan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potongan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpotongan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potongan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/potongan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpotongan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potongan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/potongan.html','potongan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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