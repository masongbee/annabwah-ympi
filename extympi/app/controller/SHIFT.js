Ext.define('YMPI.controller.SHIFT',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_shift','MASTER.v_detilshift','MASTER.v_shiftjamkerja','MASTER.v_shiftjamkerja_form'],
	models: ['m_shift','m_detilshift','m_shiftjamkerja'],
	stores: ['s_shift','s_detilshift','s_shiftjamkerja'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listshift',
		selector: 'Listshift'
	},{
		ref: 'Listshiftjamkerja',
		selector: 'Listshiftjamkerja'
	},{
		ref: 'Listdetilshift',
		selector: 'Listdetilshift'
	}],


	init: function(){
		this.control({
			'Listshift': {
				'afterrender': this.shiftAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listshift button[action=create]': {
				click: this.createRecord
			},
			'Listshift button[action=delete]': {
				click: this.deleteRecord
			},
			'Listshift button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listshift button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listshift button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	shiftAfterRender: function(){
		var shiftStore = this.getListshift().getStore();
		shiftStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_shift');
		var r = Ext.ModelManager.create({
		NAMASHIFT		: '',VALIDFROM		: '',VALIDTO		: '',KETERANGAN		: ''}, model);
		this.getListshift().getStore().insert(0, r);
		this.getListshift().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		//this.getListshift().down('#btndelete').setDisabled(!selections.length);
		if (selections.length) {
			var sel = selections[0].data;
			
			this.getListdetilshift().getStore().load({
				params: {
					NAMASHIFT: sel.NAMASHIFT
				}
			});
		}
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListshift().getStore();
		var selection = this.getListshift().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NAMASHIFT = "'+selection.data.NAMASHIFT+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_shift/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_shift/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/shift.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListshift().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_shift/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/shift.html','shift_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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