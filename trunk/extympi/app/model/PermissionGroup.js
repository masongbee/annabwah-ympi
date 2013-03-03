Ext.define('YMPI.model.PermissionGroup', {
    extend: 'Ext.data.Model',
    alias		: 'widget.PermissionGroupModel',
    fields		: ['PERM_ID', 'TREE_MENU_TITLE', 'PERM_GROUP', {name: 'PERM_PRIV', type: 'bool'}, {name: 'DEPTH', type: 'int'} ]
});
