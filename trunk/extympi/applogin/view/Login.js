/*Ext.define('YMPILogin.view.Login', {
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
                { allowBlank:false, fieldLabel: 'Password', name: 'pass', emptyText: 'password', inputType: 'password' },
                { xtype: 'filefield',allowBlank:true, fieldLabel: 'VIP Key', name: 'ffile', emptyText: 'load file for special user'}
            ],
			
			buttons: [{
				text: 'Login',
				handler: function() {
				var msg = function(title, msg) {
					Ext.Msg.show({
						title: title,
						msg: msg,
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.INFO,
						buttons: Ext.Msg.OK
					});
				};
					var form = this.up('form').getForm();
					var redirect = '';
					if(form.isValid()){						
						form.submit({
							url: 'c_action/upload',
							//waitMsg: 'Login Authentication...',
							success: function(form, action) {
								//msg('Login Success', 'Access Granted');
								//msg('Login Success', action.response.responseText);
								redirect = 'home';
								window.location = redirect;
							}
							,
							failure: function(form, action) {
								msg('Login Failed','Access Denied');
								//msg('Login Failed', action.response.responseText);
							}
						});
					}
				}
			},
			{
				text: 'Reset',
				handler: function() {
					this.up('form').getForm().reset();
				}
			}]
        }
    ]
});
*/



Ext.define('YMPILogin.view.Login', {
	extend: 'Ext.Container',
	requires: [
		'Ext.form.Panel',
		'Ext.form.field.Checkbox',
		'Ext.form.field.Text'
    ],
    
    alias	: 'widget.login',
    
	layout: {
        type: 'vbox',
        align: 'stretch'
    },
    
	items : [{
		xtype: 'form',            
		title: 'Login',
		frame:true,
		bodyPadding: 13,
		collapsible: true,
		//collapsed: true,
		defaultType: 'textfield',
		defaults: { anchor: '100%' },
		
		items: [
			{ allowBlank:false, fieldLabel: 'User ID', name: 'user', emptyText: 'user id' },
			{ allowBlank:false, fieldLabel: 'Password', name: 'pass', emptyText: 'password', inputType: 'password' },
			{ xtype: 'filefield',allowBlank:true, fieldLabel: 'VIP Key', name: 'ffile', emptyText: 'load file for special user'}
		],
		
		buttons: [{
			text: 'Login',
			handler: function() {
			var msg = function(title, msg) {
				Ext.Msg.show({
					title: title,
					msg: msg,
					minWidth: 200,
					modal: true,
					icon: Ext.Msg.INFO,
					buttons: Ext.Msg.OK
				});
			};
				var form = this.up('form').getForm();
				var redirect = '';
				if(form.isValid()){						
					form.submit({
						url: 'c_action/upload',
						success: function(form, action) {
							redirect = 'home';
							window.location = redirect;
						}
						,
						failure: function(form, action) {
							msg('Login Failed','Access Denied');
						}
					});
				}
			}
		},
		{
			text: 'Reset',
			handler: function() {
				this.up('form').getForm().reset();
			}
		}]
	},{
		xtype	: 'panel',
		title	: 'Daftar Menu',
		html	: 'ini list icon menu'
	}]

});
