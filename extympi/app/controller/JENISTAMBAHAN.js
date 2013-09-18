Ext.define('YMPI.controller.JENISTAMBAHAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_jenistambahan'],
	models: ['m_jenistambahan'],
	stores: ['s_jenistambahan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listjenistambahan',
		selector: 'Listjenistambahan'
	}],


	init: function(){
		this.control({
			'Listjenistambahan': {
				'afterrender': this.jenistambahanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listjenistambahan button[action=create]': {
				click: this.createRecord
			},
			'Listjenistambahan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listjenistambahan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listjenistambahan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listjenistambahan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	jenistambahanAfterRender: function(){
		var jenistambahanStore = this.getListjenistambahan().getStore();
		jenistambahanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_jenistambahan');
		var r = Ext.ModelManager.create({
		KODEUPAH		: '',NAMAUPAH		: '',POSCETAK		: ''}, model);
		this.getListjenistambahan().getStore().insert(0, r);
		this.getListjenistambahan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListjenistambahan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListjenistambahan().getStore();
		var selection = this.getListjenistambahan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODEUPAH = "'+selection.data.KODEUPAH+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListjenistambahan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenistambahan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListjenistambahan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenistambahan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/jenistambahan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListjenistambahan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenistambahan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/jenistambahan.html','jenistambahan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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