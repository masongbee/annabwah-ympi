Ext.define('YMPI.controller.UserManager',{
	extend: 'Ext.app.Controller',
	views: ['file.User', 'file.UserGroup', 'file.PermissionGroup'],
	models: ['User', 'UserGroup', 'PermissionGroup'],
	stores: ['User', 'UserGroup', 'PermissionGroup'],
	
	requires: [],
	
	refs: [{
		ref: 'UserGroupGrid',
		selector: 'UserGroupGrid'
	},{
		ref: 'UserGroupModel',
		selector: 'UserGroupModel'
	},{
		ref: 'PermissionGroupGrid',
		selector: 'PermissionGroupGrid'
	},{
		ref: 'UserGrid',
		selector: 'UserGrid'
	}],


	init: function(){
		this.control({
			'UserGroupGrid': {
				'selectionchange': this.enableDeleteGroup
			},
			'UserGroupGrid button[action=create]': {
				click: this.createRecordGroup
			},
			'UserGroupGrid button[action=delete]': {
				click: this.deleteRecordGroup
			},
			'PermissionGroupGrid button[action=save]': {
				click: this.saveRecordsPermission
			}
		});
	},
	
	enableDeleteGroup: function(dataview, selections){
		var getPermissionGroupPanel = this.getPermissionGroupGrid();
		var getPermissionGroupStore = this.getPermissionGroupGrid().getStore();
		var getUserStore = this.getUserGrid().getStore();
		if(selections.length){
			this.getUserGroupGrid().down('#btndelete').setDisabled(!selections.length);
			
			var group_id = selections[0].data.GROUP_ID;
			var group_name = selections[0].data.GROUP_NAME;
			getPermissionGroupPanel.setTitle('Permissions - ['+group_name+'] Group');
			
			/*jabStore.clearFilter(true);
			jabStore.filter("KODEUNIT", kodeunit);
			jabStore.load();*/
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
			getPermissionGroupPanel.setTitle('Permissions');
		}
	},
	
	createRecordGroup: function(){
		var grid = this.getUserGroupGrid();
		var selections = grid.getSelectionModel().getSelection();
		var index = 0;
		var r = Ext.ModelManager.create({
			GROUP_ID	: 0,
		    GROUP_NAME	: '',
		    GROUP_DESC	: ''
		}, this.getUserGroupModel());
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordGroup: function(dataview, selections){
		var usergroupgrid = this.getUserGroupGrid();
		var getstore = this.getUserGroupGrid().getStore();
		var selection = this.getUserGroupGrid().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	usergroupgrid.down('#btndelete').setDisabled(true);
			    	
			    	getstore.remove(selection);
			    	getstore.sync();
			    }
			});
			
		}
	},
	
	saveRecordsPermission: function(){
		var getPermissionGroupStore = this.getPermissionGroupGrid().getStore();
		getPermissionGroupStore.sync();
	}
	
});