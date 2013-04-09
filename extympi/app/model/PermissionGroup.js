Ext.define('YMPI.model.PermissionGroup', {
    extend: 'Ext.data.Model',
    alias		: 'widget.PermissionGroupModel',
    fields		: ['PERM_ID'
          		   ,'TREE_MENU_TITLE'
          		   ,'PERM_GROUP'
          		   ,'PERM_MENU'
          		   ,{name: 'PERM_PRIV', type: 'bool'}
          		   ,'MENU_PARENT'
          		   ,{name: 'DEPTH', type: 'int'} ]
});
