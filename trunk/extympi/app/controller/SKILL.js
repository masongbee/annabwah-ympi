Ext.define('YMPI.controller.SKILL',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_skill'],
	models: ['m_skill'],
	stores: ['s_skill'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listskill',
		selector: 'Listskill'
	}, {
		ref: 'Listkaryawan',
		selector: 'Listkaryawan'
	}],


	init: function(){
		this.control({
			'Listskill': {
				'afterrender': this.skillAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listskill button[action=create]': {
				click: this.createRecord
			},
			'Listskill button[action=delete]': {
				click: this.deleteRecord
			},
			'Listskill button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listskill button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listskill button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	skillAfterRender: function(){
		var skillStore = this.getListskill().getStore();
		skillStore.load();
	},
	
	createRecord: function(){
		var selection_karyawan = this.getListkaryawan().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_skill');
		var r = Ext.ModelManager.create({
			NIK			: selection_karyawan.data.NIK,
			NOURUT		: '',
			NAMASKILL	: '',
			KETERANGAN	: ''
		}, model);
		this.getListskill().getStore().insert(0, r);
		this.getListskill().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListskill().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListskill().getStore();
		var selection = this.getListskill().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListskill().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_skill/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListskill().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_skill/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/skill.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListskill().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_skill/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/skill.html','skill_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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