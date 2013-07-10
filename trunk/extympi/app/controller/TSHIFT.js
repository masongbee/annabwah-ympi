Ext.define('YMPI.controller.TSHIFT',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tshift'],
	models: ['m_tshift'],
	stores: ['s_tshift'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtshift',
		selector: 'Listtshift'
	}],


	init: function(){
		this.control({
			'Listtshift': {
				'afterrender': this.tshiftAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtshift button[action=create]': {
				click: this.createRecord
			},
			'Listtshift button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtshift button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtshift button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtshift button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tshiftAfterRender: function(){
		var tshiftStore = this.getListtshift().getStore();
		tshiftStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tshift');
		var r = Ext.ModelManager.create({
		VALIDFROM		: '',NOURUT		: '',NIK		: '',GRADE		: '',KODEJAB		: '',SHIFTKE		: '',RPTSHIFT		: '',FPENGALI		: '',USERNAME		: ''}, model);
		this.getListtshift().getStore().insert(0, r);
		this.getListtshift().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtshift().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtshift().getStore();
		var selection = this.getListtshift().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tshift/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tshift/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tshift.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tshift/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tshift.html','tshift_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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