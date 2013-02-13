Ext.define('KitchenSinkLogin.view.Login', {
    extend: 'Ext.Container',
    requires: [
		'Ext.form.Panel',
		'Ext.form.field.Checkbox',
		'Ext.form.field.Text'
    ],
    
    alias	: 'widget.login',
    
    defaults: {
        width: 400,
        height: 295
    },

    items: [
        {
            xtype: 'form',
            
            title: 'Login',
            frame:true,
            bodyPadding: 13,
            height: null,
            
            defaultType: 'textfield',
            defaults: { anchor: '100%' },
            
            items: [
                { allowBlank:false, fieldLabel: 'User ID', name: 'user', emptyText: 'user id' },
                { allowBlank:false, fieldLabel: 'Password', name: 'pass', emptyText: 'password', inputType: 'password' }
                //,{ xtype:'checkbox', fieldLabel: 'Remember me', name: 'remember' }
            ],
            
            buttons: [
                {text:'Login', action: 'loginBtn'}
            ]
        }
    ]
});
