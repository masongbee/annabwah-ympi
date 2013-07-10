Ext.define('YMPI.controller.TAMBAHAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_tambahan'],
	models: ['m_tambahan'],
	stores: ['s_tambahan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtambahan',
		selector: 'Listtambahan'
	}],


	init: function(){
		this.control({
			'Listtambahan': {
				'afterrender': this.tambahanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtambahan button[action=create]': {
				click: this.createRecord
			},
			'Listtambahan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtambahan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtambahan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtambahan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tambahanAfterRender: function(){
		var tambahanStore = this.getListtambahan().getStore();
		tambahanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tambahan');
		var r = Ext.ModelManager.create({
		BULAN		: '',NOURUT		: '',NIK		: '',GRADE		: '',KETERANGAN		: '',KODEJAB		: '',JUMLAH		: '',USERNAME		: ''}, model);
		this.getListtambahan().getStore().insert(0, r);
		this.getListtambahan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtambahan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtambahan().getStore();
		var selection = this.getListtambahan().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtambahan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tambahan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtambahan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tambahan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tambahan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtambahan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tambahan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tambahan.html','tambahan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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