Ext.define('YMPI.controller.TPEKERJAAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tpekerjaan'],
	models: ['m_tpekerjaan'],
	stores: ['s_tpekerjaan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtpekerjaan',
		selector: 'Listtpekerjaan'
	}],


	init: function(){
		this.control({
			'Listtpekerjaan': {
				'afterrender': this.tpekerjaanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtpekerjaan button[action=create]': {
				click: this.createRecord
			},
			'Listtpekerjaan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtpekerjaan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtpekerjaan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtpekerjaan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tpekerjaanAfterRender: function(){
		var tpekerjaanStore = this.getListtpekerjaan().getStore();
		tpekerjaanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tpekerjaan');
		var r = Ext.ModelManager.create({
		VALIDFROM		: '',NOURUT		: '',NIK		: '',KATPEKERJAAN		: '',RPTPEKERJAAN		: '',FPENGALI		: '',USERNAME		: '',GRADE		: ''}, model);
		this.getListtpekerjaan().getStore().insert(0, r);
		this.getListtpekerjaan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtpekerjaan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtpekerjaan().getStore();
		var selection = this.getListtpekerjaan().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtpekerjaan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tpekerjaan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtpekerjaan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tpekerjaan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tpekerjaan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtpekerjaan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tpekerjaan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tpekerjaan.html','tpekerjaan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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