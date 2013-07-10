Ext.define('YMPI.controller.KALENDERLIBUR',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_kalenderlibur'],
	models: ['m_kalenderlibur'],
	stores: ['s_kalenderlibur'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listkalenderlibur',
		selector: 'Listkalenderlibur'
	}],


	init: function(){
		this.control({
			'Listkalenderlibur': {
				'afterrender': this.kalenderliburAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listkalenderlibur button[action=filter]': {
				click: this.filterData
			},
			'Listkalenderlibur button[action=create]': {
				click: this.createRecord
			},
			'Listkalenderlibur button[action=delete]': {
				click: this.deleteRecord
			},
			'Listkalenderlibur button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listkalenderlibur button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listkalenderlibur button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	kalenderliburAfterRender: function(){
		var kalenderliburStore = this.getListkalenderlibur().getStore();
		kalenderliburStore.load();
	},
	
	filterData: function(){
		var getListkalenderlibur = this.getListkalenderlibur();
		var tglmulai_filter = getListkalenderlibur.down('#tglmulai').getValue();
		var tglsampai_filter = getListkalenderlibur.down('#tglsampai').getValue();
		
		getListkalenderlibur.getStore().proxy.extraParams.tglmulai = tglmulai_filter;
		getListkalenderlibur.getStore().proxy.extraParams.tglsampai = tglsampai_filter;
		getListkalenderlibur.getStore().load();
	},
	
	/*filterData: function(){
		var getListkalenderlibur = this.getListkalenderlibur();
		var tglmulai_filter = getListkalenderlibur.down('#tglmulai').getValue();
		var tglsampai_filter = getListkalenderlibur.down('#tglsampai').getValue();
		
		var tglm = tglmulai_filter.format("yyyy-mm-dd");
		var tgls = tglsampai_filter.format("yyyy-mm-dd");
		//console.info(bulan_filter+" "+tglmulai_filter.format("yyyy-mm-dd")+" "+tglsampai_filter.format("yyyy-mm-dd"));
		console.info(tglm+" "+tgls);
		
		var me = this;
		var msg = function(title, msg) {
			Ext.Msg.show({
				title: title,
				msg: msg,
				minWidth: 200,
				modal: true,
				icon: Ext.Msg.INFO,
				buttons: Ext.Msg.OK
			});
		};
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kalenderlibur/FilterData/'+tglm+'/'+tgls,
			waitMsg: 'Filter Data...',
			success: function(response){
				msg('Success', 'Data Telah Diproses...');
				//msg('Login Success', action.response.responseText);
				me.kalenderliburAfterRender();
			},
			failure: function(response) {
				msg('Failed','Data Gagal Diproses...');
				//msg('Login Failed', action.response.responseText);
			}
		});
	},*/
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_kalenderlibur');
		var r = Ext.ModelManager.create({
		TANGGAL		: '',JENISLIBUR		: '',AGAMA		: '',KETERANGAN		: '',USERNAME		: username}, model);
		this.getListkalenderlibur().getStore().insert(0, r);
		this.getListkalenderlibur().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListkalenderlibur().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListkalenderlibur().getStore();
		var selection = this.getListkalenderlibur().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListkalenderlibur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kalenderlibur/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListkalenderlibur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kalenderlibur/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/kalenderlibur.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListkalenderlibur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_kalenderlibur/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/kalenderlibur.html','kalenderlibur_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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