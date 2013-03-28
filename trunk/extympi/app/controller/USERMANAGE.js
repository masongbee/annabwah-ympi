Ext.define('YMPI.controller.USERMANAGE',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.User', 'AKSES.UserGroup', 'AKSES.PermissionGroup'],
	models: ['User', 'UserGroup', 'PermissionGroup'],
	stores: ['User', 'UserGroup', 'PermissionGroup'],
	
	requires: [],
	
	refs: [{
		ref: 'UserGroupList',
		selector: 'UserGroupList'
	},{
		ref: 'PermissionGroupList',
		selector: 'PermissionGroupList'
	},{
		ref: 'UserList',
		selector: 'UserList'
	}],


	init: function(){
		this.control({
			'UserGroupList': {
				'selectionchange': this.enableDeleteGroup
			},
			'UserGroupList button[action=create]': {
				click: this.createRecordGroup
			},
			'UserGroupList button[action=delete]': {
				click: this.deleteRecordGroup
			},
			'PermissionGroupList button[action=save]': {
				click: this.saveRecordsPermission
			},
			'UserList': {
				'selectionchange': this.enableDeleteUser
			},
			'UserList button[action=create]': {
				click: this.createRecordUser
			},
			'UserList button[action=delete]': {
				click: this.deleteRecordUser
			}
		});
	},
	
	//[breakpoint]
	enableDeleteGroup: function(dataview, selections){
		/*
		 * Collect Data
		 */
		var getUserGroupList		= this.getUserGroupList();
		var getPermissionGroupList 	= this.getPermissionGroupList();
		var getPermissionGroupStore = this.getPermissionGroupList().getStore();
		var getUserList 			= this.getUserList();
		var getUserStore 			= this.getUserList().getStore();
		
		/*
		 * Action ketika row di User Group dipilih
		 */
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
			getUserGroupList.down('#btndelete').setDisabled(!selections.length);
			getPermissionGroupList.down('#btnsave').setDisabled(!selections.length);
			getUserList.down('#btnadd').setDisabled(!selections.length);
			getUserList.down('#btndelete').setDisabled(!selections.length);
			
			var group_id = selections[0].data.GROUP_ID;
			var group_name = selections[0].data.GROUP_NAME;
			getPermissionGroupList.setTitle('Permissions - ['+group_name+' - Group]');
			getUserList.setTitle('Users - ['+group_name+' - Group]');
			
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
			/*
			 * Jika row pada UserGroupList tidak ada yang terpilih
			 */
			getPermissionGroupList.setTitle('Permissions');
			getUserList.setTitle('Users');
			
			getUserGroupList.down('#btndelete').setDisabled(!selections.length);
			getPermissionGroupList.down('#btnsave').setDisabled(!selections.length);
			getUserList.down('#btnadd').setDisabled(!selections.length);
			getUserList.down('#btndelete').setDisabled(!selections.length);
			
			getPermissionGroupStore.loadData([],false);
			getUserStore.loadData([],false);
		}
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.UserGroup');
		var grid 		= this.getUserGroupList();
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
	
	deleteRecordGroup: function(){
		var usergrouplist = this.getUserGroupList();
		var getstore = this.getUserGroupList().getStore();
		var selection = this.getUserGroupList().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	usergrouplist.down('#btndelete').setDisabled(true);
			    	
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	},
	
	saveRecordsPermission: function(){
		var getUserGroupList = this.getUserGroupList(),
			group_id 	= getUserGroupList.getSelectionModel().getSelection()[0].data.GROUP_ID;
		var getPermissionGroupStore = this.getPermissionGroupList().getStore();
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
			this.getUserList().down('#btndelete').setDisabled(!selections.length);
		}
	},
	
	createRecordUser: function(){
		var getUserGroupList = this.getUserGroupList(),
			group_id 	= getUserGroupList.getSelectionModel().getSelection()[0].data.GROUP_ID;
		var model		= Ext.ModelMgr.getModel('YMPI.model.User');
		var grid 		= this.getUserList();
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
		var userlist = this.getUserList(),
			getstore = userlist.getStore(),
			selection = userlist.getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: User = \"'+selection.data.USER_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	userlist.down('#btndelete').setDisabled(true);
			    	
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	}
	
});