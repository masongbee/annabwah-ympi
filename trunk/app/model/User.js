Ext.define('YMPI.model.User', {
    extend: 'Ext.data.Model',
    alias		: 'widget.UserModel',
    fields		: ['USER_ID', 'USER_NAME', 'NIK', 'NAMAKAR'],
	idProperty	: 'USER_NAME'
});
