Ext.define('YMPI.controller.TKELUARGA',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tkeluarga'],
	models: ['m_tkeluarga'],
	stores: ['s_tkeluarga'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtkeluarga',
		selector: 'Listtkeluarga'
	}],


	init: function(){
		this.control({
			'Listtkeluarga': {
				'afterrender': this.tkeluargaAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtkeluarga button[action=create]': {
				click: this.createRecord
			},
			'Listtkeluarga button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtkeluarga button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtkeluarga button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtkeluarga button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tkeluargaAfterRender: function(){
		var tkeluargaStore = this.getListtkeluarga().getStore();
		tkeluargaStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tkeluarga');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			NOURUT		: '',
			GRADE		: '',
			KODEJAB		: '',
			NIK			: '',
			STATUSKEL2	: '',
			UMURTO		: '',
			PELAJAR		: '',
			RPTKELUARGA	: '',
			USERNAME	: username
		}, model);
		this.getListtkeluarga().getStore().insert(0, r);
		this.getListtkeluarga().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtkeluarga().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtkeluarga().getStore();
		var selection = this.getListtkeluarga().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtkeluarga().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkeluarga/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtkeluarga().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkeluarga/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tkeluarga.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtkeluarga().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkeluarga/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tkeluarga.html','tkeluarga_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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