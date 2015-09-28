Ext.define('YMPI.controller.REKAPJEMPUTAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_rekapjemputan'],
	models: ['m_rekapjemputan'],
	stores: ['s_rekapjemputan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listrekapjemputan',
		selector: 'Listrekapjemputan'
	}],


	init: function(){
		this.control({
			'Listrekapjemputan': {
				'afterrender': this.rekapjemputanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listrekapjemputan button[action=create]': {
				click: this.createRecord
			},
			'Listrekapjemputan button[action=delete]': {
				click: this.deleteRecord
			}/*,
			'Listrekapjemputan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listrekapjemputan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listrekapjemputan button[action=print]': {
				click: this.printRecords
			}*/
		});
	},
	
	rekapjemputanAfterRender: function(){
		var rekapjemputanStore = this.getListrekapjemputan().getStore();
		rekapjemputanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_rekapjemputan');
		var r = Ext.ModelManager.create({
			NIK         : '',
			BULAN		: '',
			JMLJEMPUT	: '',
			KETERANGAN	: ''
		}, model);
		this.getListrekapjemputan().getStore().insert(0, r);
		this.getListrekapjemputan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListrekapjemputan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListrekapjemputan().getStore();
		var selection = this.getListrekapjemputan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NIK = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	}/*,
	
	export2Excel: function(){
		var getstore = this.getListrekapjemputan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListrekapjemputan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/rekapjemputan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListrekapjemputan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/rekapjemputan.html','rekapjemputan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	}*/
	
});