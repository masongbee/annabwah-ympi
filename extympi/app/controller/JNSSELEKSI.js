Ext.define('YMPI.controller.JNSSELEKSI',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_jnsseleksi'],
	models: ['m_jnsseleksi'],
	stores: ['s_jnsseleksi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listjnsseleksi',
		selector: 'Listjnsseleksi'
	}],


	init: function(){
		this.control({
			'Listjnsseleksi': {
				'afterrender': this.jnsseleksiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listjnsseleksi button[action=create]': {
				click: this.createRecord
			},
			'Listjnsseleksi button[action=delete]': {
				click: this.deleteRecord
			},
			'Listjnsseleksi button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listjnsseleksi button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listjnsseleksi button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	jnsseleksiAfterRender: function(){
		var jnsseleksiStore = this.getListjnsseleksi().getStore();
		jnsseleksiStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_jnsseleksi');
		var r = Ext.ModelManager.create({
			KODESELEKSI		: '',
			NAMASELEKSI		: ''
		}, model);
		this.getListjnsseleksi().getStore().insert(0, r);
		this.getListjnsseleksi().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListjnsseleksi().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListjnsseleksi().getStore();
		var selection = this.getListjnsseleksi().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODESELEKSI = "'+selection.data.KODESELEKSI+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListjnsseleksi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jnsseleksi/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListjnsseleksi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jnsseleksi/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/jnsseleksi.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListjnsseleksi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jnsseleksi/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/jnsseleksi.html','jnsseleksi_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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