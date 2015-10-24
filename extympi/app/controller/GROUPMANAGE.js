Ext.define('YMPI.controller.GROUPMANAGE',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.UserGroup', 'AKSES.Permission'],
	models: ['UserGroups', 'Permissions'],
	stores: ['UserGroups', 'Permissions'],
	
	requires: [],
	
	refs: [{
		ref: 'UserGroup',
		selector: 'UserGroup'
	},{
		ref: 'Permission',
		selector: 'Permission'
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
			'Permission button[action=save]': {
				click: this.saveRecordsPermission
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
		
		if(selections.length){
			/*
			 * Enable button Delete di view.AKSES.UserGroup
			 * Enable button Save di view.AKSES.Permission
			 * 
			 * #btndelete == property "itemId" di masing-masing view
			 * 
			 */
			getPermission.down('#btnsave').setDisabled(!selections.length);
			getUserGroup.down('#btndelete').setDisabled(!selections.length);
			
			var group_id = selections[0].data.GROUP_ID;
			var group_name = selections[0].data.GROUP_NAME;
			getPermission.setTitle('Permission - ['+group_name+' - Group]');
			// getUser.setTitle('User - ['+group_name+' - Group]');
			
			getPermissionStore.load({
				params: {
					GROUP_ID: group_id
				}
			});
			
		}else{
			getPermission.setTitle('Permission');
			
			getUserGroup.down('#btndelete').setDisabled(!selections.length);
			getPermission.down('#btnsave').setDisabled(!selections.length);
			
			getPermissionStore.loadData([],false);
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
	}
	
});