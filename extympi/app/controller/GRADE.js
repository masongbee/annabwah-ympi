Ext.define('YMPI.controller.GRADE',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_grade'],
	models: ['m_grade'],
	stores: ['s_grade'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listgrade',
		selector: 'Listgrade'
	}],


	init: function(){
		this.control({
			'Listgrade': {
				'afterrender': this.gradeAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listgrade button[action=create]': {
				click: this.createRecord
			},
			'Listgrade button[action=delete]': {
				click: this.deleteRecord
			},
			'Listgrade button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listgrade button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listgrade button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	gradeAfterRender: function(){
		var gradeStore = this.getListgrade().getStore();
		gradeStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_grade');
		var r = Ext.ModelManager.create({
            GRADE: '',
            KETERANGAN: ''
            }, model);
		this.getListgrade().getStore().insert(0, r);
		this.getListgrade().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListgrade().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListgrade().getStore();
		var selection = this.getListgrade().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: GRADE = "'+selection.data.GRADE+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListgrade().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_grade/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListgrade().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_grade/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/grade.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListgrade().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_grade/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/grade.html','grade_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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