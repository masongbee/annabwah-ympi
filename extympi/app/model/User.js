Ext.define('YMPI.model.User', {
    extend: 'Ext.data.Model',
    alias		: 'widget.UserModel',
    fields		: ['USER_ID'
          		   ,'USER_NAME'
          		   ,'USER_PASSWD'
          		   ,'NIK'
          		   ,'NAMAKAR'
          		   ,'GROUP_ID'
          		   ,{name: 'VIP_USER', type: 'bool'}],
	idProperty	: 'USER_NAME'
});
