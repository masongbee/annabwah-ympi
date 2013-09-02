Ext.define('YMPI.controller.JEMPUTANKAR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_jemputankar'],
	models: ['m_jemputankar'],
	stores: ['s_jemputankar'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listjemputankar',
		selector: 'Listjemputankar'
	}],


	init: function(){
		this.control({
			'Listjemputankar': {
				'afterrender': this.jemputankarAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listjemputankar button[action=create]': {
				click: this.createRecord
			},
			'Listjemputankar button[action=delete]': {
				click: this.deleteRecord
			},
			'Listjemputankar button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listjemputankar button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listjemputankar button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	jemputankarAfterRender: function(){
		var jemputankarStore = this.getListjemputankar().getStore();
		jemputankarStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_jemputankar');
		var r = Ext.ModelManager.create({
		NAMASHIFT		: '',SHIFTKE		: '',NIK		: '',TANGGAL		: '',ZONA		: '',IKUTJEMPUTAN		: '',KETERANGAN		: ''}, model);
		this.getListjemputankar().getStore().insert(0, r);
		this.getListjemputankar().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListjemputankar().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListjemputankar().getStore();
		var selection = this.getListjemputankar().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TANGGAL = "'+selection.data.TANGGAL+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListjemputankar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jemputankar/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListjemputankar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jemputankar/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/jemputankar.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListjemputankar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jemputankar/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/jemputankar.html','jemputankar_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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