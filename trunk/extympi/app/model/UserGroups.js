Ext.define('YMPI.model.UserGroups', {
    extend: 'Ext.data.Model',
    fields		: ['GROUP_ID', 'GROUP_NAME', 'GROUP_DESC', {name: 'GROUP_USER', type: 'bool'}],
	idProperty	: 'GROUP_ID'
});
