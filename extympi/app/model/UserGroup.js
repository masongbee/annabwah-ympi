Ext.define('YMPI.model.UserGroup', {
    extend: 'Ext.data.Model',
    alias		: 'widget.UserGroupModel',
    fields		: ['GROUP_ID', 'GROUP_NAME', 'GROUP_DESC'],
	idProperty	: 'GROUP_ID'
});
