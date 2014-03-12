Ext.define('YMPI.controller.USERMANAGE',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.User', 'AKSES.UserGroup', 'AKSES.Permission'],
	models: ['Users', 'UserGroups', 'Permissions'],
	stores: ['Users', 'UserGroups', 'Permissions'],
	
	requires: [],
	
	refs: [{
		ref: 'UserGroup',
		selector: 'UserGroup'
	},{
		ref: 'Permission',
		selector: 'Permission'
	},{
		ref: 'User',
		selector: 'User'
	}],


	init: function(){
		this.control({
			'UserGroup': {
				'afterrender': this.usergroupAfterRender,
				'selectionchange': this.enableDeleteGroup
			},
			'UserGroup button[action=create]': {
				click: this.createRecordGroup
			},
			'UserGroup button[action=delete]': {
				click: this.deleteRecordGroup
			},
			'UserGroup button[action=save]': {
				click: this.saveRecordGroup
			},
			'Permission button[action=save]': {
				click: this.saveRecordsPermission
			},
			'User': {
				'afterrender': this.userAfterRender,
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
	
	usergroupAfterRender: function(){
		var getUserGroupStore = this.getUserGroup().getStore();
		getUserGroupStore.load();
	},
	
	enableDeleteGroup: function(dataview, selections){
		var getUserGroup = this.getUserGroup();
		var getPermission = this.getPermission();
		var getPermissionStore = this.getPermission().getStore();
		// var getUser = this.getUser();
		// var getUserStore = this.getUser().getStore();
		if(selections.length){
			/*
			 * Enable button Delete di view.AKSES.UserGroup
			 * Enable button Save di view.AKSES.Permission
			 * Enable button Add di view.AKSES.User
			 * Enable button Delete di view.AKSES.User
			 * 
			 * #btndelete == property "itemId" di masing-masing view
			 * 
			 */
			getUserGroup.down('#btndelete').setDisabled(!selections.length);
			getPermission.down('#btnsave').setDisabled(!selections.length);
			// getUser.down('#btnadd').setDisabled(!selections.length);
			// getUser.down('#btndelete').setDisabled(!selections.length);
			
			var group_id = selections[0].data.GROUP_ID;
			var group_name = selections[0].data.GROUP_NAME;
			getPermission.setTitle('Permission - ['+group_name+' - Group]');
			// getUser.setTitle('User - ['+group_name+' - Group]');
			
			getPermissionStore.load({
				params: {
					GROUP_ID: group_id
				}
			});
			// getUserStore.load({
			// 	params: {
			// 		GROUP_ID: group_id
			// 	}
			// });
		}else{
			getPermission.setTitle('Permission');
			// getUser.setTitle('User');
			
			getUserGroup.down('#btndelete').setDisabled(!selections.length);
			getPermission.down('#btnsave').setDisabled(!selections.length);
			// getUser.down('#btnadd').setDisabled(!selections.length);
			// getUser.down('#btndelete').setDisabled(!selections.length);
			
			getPermissionStore.loadData([],false);
			// getUserStore.loadData([],false);
		}
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.UserGroups');
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

	saveRecordGroup: function(){
		var getUser = this.getUser(),
			user_id 	= getUser.getSelectionModel().getSelection()[0].data.USER_ID;
		var getstore = this.getUserGroup().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));

		Ext.Ajax.request({
			method: 'POST',
			url: 'c_usergroups/hakuser_save',
			params: {data: jsonData, userid: user_id},
			success: function(response){
				getstore.load({
					params: {
						userid: user_id
					}
				});
			}
		});
	},
	
	saveRecordsPermission: function(){
		var getUserGroup = this.getUserGroup(),
			group_id 	= getUserGroup.getSelectionModel().getSelection()[0].data.GROUP_ID;
		var getPermissionStore = this.getPermission().getStore();
		getPermissionStore.sync({
			callback: function(rec, operation, success){
				getPermissionStore.load({
					params: {
						GROUP_ID: group_id
					}
				});
			}
		});
	},

	userAfterRender: function(){
		var getUserStore = this.getUser().getStore();
		getUserStore.load();
	},
	
	enableDeleteUser: function(dataview, selections){
		var getUserGroup = this.getUserGroup(),
			getUserGroupStore = getUserGroup.getStore();

		if(selections.length){
			var user_id = selections[0].data.USER_ID;

			this.getUser().down('#btndelete').setDisabled(!selections.length);
			getUserGroup.down('#btnsave').setDisabled(false);

			getUserGroupStore.load({
				params: {
					userid: user_id
				}
			});
		}else{
			getUserGroup.down('#btnsave').setDisabled(true);

			getUserGroupStore.load({
				params: {
					userid: 0
				}
			});

		}
	},
	
	createRecordUser: function(){
		var getUserGroup = this.getUserGroup();
		var model		= Ext.ModelMgr.getModel('YMPI.model.Users');
		var grid 		= this.getUser();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			USER_NAME	: '',
			USER_PASSWD	: '',
			VIP_USER	: 0,
			USER_KARYAWAN: ''
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