Ext.define('YMPI.model.Permissions2', {
    extend: 'Ext.data.Model',
    fields		: ['PERM_ID'
          		   ,'TREE_MENU_TITLE'
          		   ,'PERM_GROUP'
          		   ,'PERM_MENU'
          		   ,{name: 'PERM_PRIV', type: 'bool'}
          		   ,'MENU_PARENT'
          		   ,{name: 'DEPTH', type: 'int'} ],
    idProperty	: 'PERM_ID'
});
