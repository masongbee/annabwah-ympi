Ext.define('YMPI.controller.TAMBAHANLAIN2',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_tambahanlain2'],
	models: ['m_tambahanlain2'],
	stores: ['s_tambahanlain2'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtambahanlain2',
		selector: 'Listtambahanlain2'
	}],


	init: function(){
		this.control({
			'Listtambahanlain2': {
				'afterrender': this.tambahanlain2AfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtambahanlain2 button[action=create]': {
				click: this.createRecord
			},
			'Listtambahanlain2 button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtambahanlain2 button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtambahanlain2 button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtambahanlain2 button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tambahanlain2AfterRender: function(){
		var tambahanlain2Store = this.getListtambahanlain2().getStore();
		tambahanlain2Store.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tambahanlain2');
		var r = Ext.ModelManager.create({
			BULAN		: '',
			NOURUT		: '',
			KODEUPAH	: '',
			TANGGAL		: '',
			NIK			: '',
			GRADE		: '',
			KODEJAB		: '',
			KETERANGAN	: '',
			RPTAMBAHAN	: '',
			USERNAME	: username
		}, model);
		this.getListtambahanlain2().getStore().insert(0, r);
		this.getListtambahanlain2().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtambahanlain2().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtambahanlain2().getStore();
		var selection = this.getListtambahanlain2().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtambahanlain2().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tambahanlain2/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtambahanlain2().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tambahanlain2/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tambahanlain2.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtambahanlain2().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tambahanlain2/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tambahanlain2.html','tambahanlain2_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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