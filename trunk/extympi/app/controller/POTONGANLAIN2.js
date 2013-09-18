Ext.define('YMPI.controller.POTONGANLAIN2',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_potonganlain2'],
	models: ['m_potonganlain2'],
	stores: ['s_potonganlain2'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpotonganlain2',
		selector: 'Listpotonganlain2'
	}],


	init: function(){
		this.control({
			'Listpotonganlain2': {
				'afterrender': this.potonganlain2AfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpotonganlain2 button[action=create]': {
				click: this.createRecord
			},
			'Listpotonganlain2 button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpotonganlain2 button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpotonganlain2 button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpotonganlain2 button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	potonganlain2AfterRender: function(){
		var potonganlain2Store = this.getListpotonganlain2().getStore();
		potonganlain2Store.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_potonganlain2');
		var r = Ext.ModelManager.create({
			BULAN		: '',
			NOURUT		: '',
			KODEPOTONGAN: '',
			TANGGAL		: '',
			NIK			: '',
			GRADE		: '',
			KODEJAB		: '',
			KETERANGAN	: '',
			RPPOTONGAN	: '',
			USERNAME	: username
		}, model);
		this.getListpotonganlain2().getStore().insert(0, r);
		this.getListpotonganlain2().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpotonganlain2().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpotonganlain2().getStore();
		var selection = this.getListpotonganlain2().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListpotonganlain2().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potonganlain2/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpotonganlain2().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potonganlain2/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/potonganlain2.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpotonganlain2().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potonganlain2/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/potonganlain2.html','potonganlain2_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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