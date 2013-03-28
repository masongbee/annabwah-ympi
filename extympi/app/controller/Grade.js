Ext.define('YMPI.controller.GRADE',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.GRADE'],
	models: ['Grade'],
	stores: ['Grade'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'GradeList',
		selector: 'GradeList'
	}],


	init: function(){
		this.control({
			'GradeList': {
				'selectionchange': this.enableDelete
			},
			'GradeList button[action=create]': {
				click: this.createRecord
			},
			'GradeList button[action=delete]': {
				click: this.deleteRecord
			},
			'GradeList button[action=xexcel]': {
				click: this.export2Excel
			},
			'GradeList button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.Grade');
		var r = Ext.ModelManager.create({
		    GRADE		: '00',
		    KETERANGAN	: ''
		}, model);
		this.getGradeList().getStore().insert(0, r);
		this.getGradeList().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getGradeList().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getGradeList().getStore();
		var selection = this.getGradeList().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Grade = \"'+selection.data.GRADE+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getGradeList().getStore();
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
	
	printRecords: function(){
		var getstore = this.getGradeList().getStore();
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