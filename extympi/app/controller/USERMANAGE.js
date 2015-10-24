Ext.define('YMPI.controller.USERMANAGE',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.User', 'AKSES.UserGroup2', 'AKSES.Permission2'],
	models: ['Users', 'UserGroups', 'Permissions2'],
	stores: ['Users', 'UserGroups', 'Permissions2'],
	
	requires: [],
	
	refs: [{
		ref: 'UserGroup2',
		selector: 'UserGroup2'
	},{
		ref: 'Permission2',
		selector: 'Permission2'
	},{
		ref: 'User',
		selector: 'User'
	}],


	init: function(){
		this.control({
			'UserGroup2': {
				'afterrender': this.usergroupAfterRender,
				'selectionchange': this.enableDeleteGroup
			},
			/*'UserGroup2 button[action=create]': {
				click: this.createRecordGroup
			},
			'UserGroup2 button[action=delete]': {
				click: this.deleteRecordGroup
			},*/
			'UserGroup2 button[action=save]': {
				click: this.saveRecordGroup
			},
			/*'Permission2 button[action=save]': {
				click: this.saveRecordsPermission
			},*/
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
		var getUserGroup2Store = this.getUserGroup2().getStore();
		getUserGroup2Store.load();
	},
	
	enableDeleteGroup: function(dataview, selections){
		var getUserGroup2 = this.getUserGroup2();
		var getPermission2 = this.getPermission2();
		var getPermission2Store = this.getPermission2().getStore();
		// var getUser = this.getUser();
		// var getUserStore = this.getUser().getStore();
		if(selections.length){
			/*
			 * Enable button Delete di view.AKSES.UserGroup2
			 * Enable button Save di view.AKSES.Permission2
			 * Enable button Add di view.AKSES.User
			 * Enable button Delete di view.AKSES.User
			 * 
			 * #btndelete == property "itemId" di masing-masing view
			 * 
			 */
			// getUserGroup2.down('#btndelete').setDisabled(!selections.length);
			// getPermission2.down('#btnsave').setDisabled(!selections.length);
			// getUser.down('#btnadd').setDisabled(!selections.length);
			// getUser.down('#btndelete').setDisabled(!selections.length);
			
			var group_id = selections[0].data.GROUP_ID;
			var group_name = selections[0].data.GROUP_NAME;
			getPermission2.setTitle('Permission - ['+group_name+' - Group]');
			// getUser.setTitle('User - ['+group_name+' - Group]');
			
			getPermission2Store.load({
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
			getPermission2.setTitle('Permission');
			// getUser.setTitle('User');
			
			// getUserGroup2.down('#btndelete').setDisabled(!selections.length);
			// getPermission2.down('#btnsave').setDisabled(!selections.length);
			// getUser.down('#btnadd').setDisabled(!selections.length);
			// getUser.down('#btndelete').setDisabled(!selections.length);
			
			getPermission2Store.loadData([],false);
			// getUserStore.loadData([],false);
		}
	},
	
	/*createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.UserGroups');
		var grid 		= this.getUserGroup2();
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
		var getUserGroup2 = this.getUserGroup2(),
			getUserGroup2Store = getUserGroup2.getStore();
		var selection = this.getUserGroup2().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	// getUserGroup2.down('#btndelete').setDisabled(true);
			    	
			    	getUserGroup2Store.remove(selection);
			    	getUserGroup2Store.sync();
			    }
			});
			
		}
	},*/

	saveRecordGroup: function(){
		var getUser = this.getUser(),
			user_id 	= getUser.getSelectionModel().getSelection()[0].data.USER_ID;
		var getstore = this.getUserGroup2().getStore();
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
	
	/*saveRecordsPermission: function(){
		var getUserGroup2 = this.getUserGroup2(),
			group_id 	= getUserGroup2.getSelectionModel().getSelection()[0].data.GROUP_ID;
		var getPermission2Store = this.getPermission2().getStore();
		getPermission2Store.sync({
			callback: function(rec, operation, success){
				getPermission2Store.load({
					params: {
						GROUP_ID: group_id
					}
				});
			}
		});
	},*/

	userAfterRender: function(){
		var getUserStore = this.getUser().getStore();
		getUserStore.load();
	},
	
	enableDeleteUser: function(dataview, selections){
		var getUserGroup2 = this.getUserGroup2(),
			getUserGroup2Store = getUserGroup2.getStore();

		if(selections.length){
			var user_id = selections[0].data.USER_ID;

			this.getUser().down('#btndelete').setDisabled(!selections.length);
			getUserGroup2.down('#btnsave').setDisabled(false);

			getUserGroup2Store.load({
				params: {
					userid: user_id
				}
			});
		}else{
			getUserGroup2.down('#btnsave').setDisabled(true);

			getUserGroup2Store.load({
				params: {
					userid: 0
				}
			});

		}
	},
	
	createRecordUser: function(){
		var getUserGroup2 = this.getUserGroup2();
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