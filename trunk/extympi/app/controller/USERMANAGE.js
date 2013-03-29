Ext.define('YMPI.controller.USERMANAGE',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.User', 'AKSES.UserGroup', 'AKSES.PermissionGroup'],
	models: ['User', 'UserGroup', 'PermissionGroup'],
	stores: ['User', 'UserGroup', 'PermissionGroup'],
	
	requires: [],
	
	refs: [{
		ref: 'UserGroup',
		selector: 'UserGroup'
	},{
		ref: 'PermissionGroup',
		selector: 'PermissionGroup'
	},{
		ref: 'User',
		selector: 'User'
	}],


	init: function(){
		this.control({
			'UserGroup': {
				'selectionchange': this.enableDeleteGroup
			},
			'UserGroup button[action=create]': {
				click: this.createRecordGroup
			},
			'UserGroup button[action=delete]': {
				click: this.deleteRecordGroup
			},
			'PermissionGroup button[action=save]': {
				click: this.saveRecordsPermission
			},
			'User': {
				'selectionchange': this.enableDeleteUser
			},
			'User button[action=create]': {
				click: this.createRecordUser
			},
			'User button[action=delete]': {
				click: this.deleteRecordUser
			}
		});
	},
	
	enableDeleteGroup: function(dataview, selections){
		var getUserGroup = this.getUserGroup();
		var getPermissionGroup = this.getPermissionGroup();
		var getPermissionGroupStore = this.getPermissionGroup().getStore();
		var getUser = this.getUser();
		var getUserStore = this.getUser().getStore();
		if(selections.length){
			/*
			 * Enable button Delete di view.AKSES.UserGroup
			 * Enable button Save di view.AKSES.PermissionGroup
			 * Enable button Add di view.AKSES.User
			 * Enable button Delete di view.AKSES.User
			 * 
			 * #btndelete == property "itemId" di masing-masing view
			 * 
			 */
			getUserGroup.down('#btndelete').setDisabled(!selections.length);
			getPermissionGroup.down('#btnsave').setDisabled(!selections.length);
			getUser.down('#btnadd').setDisabled(!selections.length);
			getUser.down('#btndelete').setDisabled(!selections.length);
			
			var group_id = selections[0].data.GROUP_ID;
			var group_name = selections[0].data.GROUP_NAME;
			getPermissionGroup.setTitle('Permission - ['+group_name+' - Group]');
			getUser.setTitle('User - ['+group_name+' - Group]');
			
			getPermissionGroupStore.load({
				params: {
					GROUP_ID: group_id
				}
			});
			getUserStore.load({
				params: {
					GROUP_ID: group_id
				}
			});
		}else{
			getPermissionGroup.setTitle('Permission');
			getUser.setTitle('User');
			
			getUserGroup.down('#btndelete').setDisabled(!selections.length);
			getPermissionGroup.down('#btnsave').setDisabled(!selections.length);
			getUser.down('#btnadd').setDisabled(!selections.length);
			getUser.down('#btndelete').setDisabled(!selections.length);
			
			getPermissionGroupStore.loadData([],false);
			getUserStore.loadData([],false);
		}
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.UserGroup');
		var grid 		= this.getUserGroup();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			GROUP_ID	: 0,
		    GROUP_NAME	: '',
		    GROUP_DESC	: ''
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordGroup: function(dataview, selections){
		var getUserGroup = this.getUserGroup(),
			getUserGroupStore = getUserGroup.getStore();
		var selection = this.getUserGroup().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getUserGroup.down('#btndelete').setDisabled(true);
			    	
			    	getUserGroupStore.remove(selection);
			    	getUserGroupStore.sync();
			    }
			});
			
		}
	},
	
	saveRecordsPermission: function(){
		var getUserGroup = this.getUserGroup(),
			group_id 	= getUserGroup.getSelectionModel().getSelection()[0].data.GROUP_ID;
		var getPermissionGroupStore = this.getPermissionGroupGrid().getStore();
		getPermissionGroupStore.sync({
			callback: function(rec, operation, success){
				getPermissionGroupStore.load({
					params: {
						GROUP_ID: group_id
					}
				});
			}
		});
	},
	
	enableDeleteUser: function(dataview, selections){
		if(selections.length){
			this.getUser().down('#btndelete').setDisabled(!selections.length);
		}
	},
	
	createRecordUser: function(){
		var getUserGroup = this.getUserGroup(),
			group_id 	= getUserGroup.getSelectionModel().getSelection()[0].data.GROUP_ID;
		var model		= Ext.ModelMgr.getModel('YMPI.model.User');
		var grid 		= this.getUser();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			USER_NAME	: '',
			USER_PASSWD	: '',
			GROUP_ID	: group_id
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordUser: function(){
		var getUser = this.getUser(),
			getUserStore = getUser.getStore(),
			selection = getUser.getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: User = \"'+selection.data.USER_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getUser.down('#btndelete').setDisabled(true);
			    	
			    	getUserStore.remove(selection);
			    	getUserStore.sync();
			    }
			});
			
		}
	}
	
});