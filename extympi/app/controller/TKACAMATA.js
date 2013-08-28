Ext.define('YMPI.controller.TKACAMATA',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tkacamata'],
	models: ['m_tkacamata'],
	stores: ['s_tkacamata'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtkacamata',
		selector: 'Listtkacamata'
	}],


	init: function(){
		this.control({
			'Listtkacamata': {
				'afterrender': this.tkacamataAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtkacamata button[action=create]': {
				click: this.createRecord
			},
			'Listtkacamata button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtkacamata button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtkacamata button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtkacamata button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tkacamataAfterRender: function(){
		var tkacamataStore = this.getListtkacamata().getStore();
		tkacamataStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tkacamata');
		var r = Ext.ModelManager.create({
			BULAN		: '',
			NIK			: '',
			TANGGAL		: '',
			RPFRAME		: '',
			RPLENSA		: '',
			USERNAME	: username
		}, model);
		this.getListtkacamata().getStore().insert(0, r);
		this.getListtkacamata().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtkacamata().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtkacamata().getStore();
		var selection = this.getListtkacamata().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NIK = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtkacamata().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkacamata/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtkacamata().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkacamata/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tkacamata.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtkacamata().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkacamata/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tkacamata.html','tkacamata_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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