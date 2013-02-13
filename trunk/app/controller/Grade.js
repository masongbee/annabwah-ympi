Ext.define('YMPI.controller.Grade',{
	extend: 'Ext.app.Controller',
	views: ['dataMaster.Grade'],
	models: ['Grade'],
	stores: ['Grade'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'gradeGrid',
		selector: 'gradeGrid'
	},{
		ref: 'gradeModel',
		selector: 'gradeModel'
	}],


	init: function(){
		this.control({
			'gradeGrid': {
				'selectionchange': this.enableDelete
			},
			'gradeGrid button[action=create]': {
				click: this.createRecord
			},
			'gradeGrid button[action=delete]': {
				click: this.deleteRecord
			},
			'gradeGrid button[action=xexcel]': {
				click: this.export2Excel
			},
			'gradeGrid button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	createRecord: function(){
		var r = Ext.ModelManager.create({
		    GRADE		: '00',
		    KETERANGAN	: ''
		}, this.getGradeModel());
		this.getGradeGrid().getStore().insert(0, r);
		this.getGradeGrid().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getGradeGrid().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getGradeGrid().getStore();
		var selection = this.getGradeGrid().getSelectionModel().getSelection()[0];
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
		var getstore = this.getGradeGrid().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'welcome/grade/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getGradeGrid().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'welcome/grade/printRecords',
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