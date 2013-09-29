Ext.define('YMPI.model.Users', {
    extend: 'Ext.data.Model',
    fields		: ['USER_ID'
          		   ,'USER_NAME'
          		   ,'USER_PASSWD'
          		   ,'USER_KARYAWAN'
          		   ,'NAMAKAR'
          		   ,'GROUP_ID'
          		   ,{name: 'VIP_USER', type: 'bool'}],
	idProperty	: 'USER_ID'
});
