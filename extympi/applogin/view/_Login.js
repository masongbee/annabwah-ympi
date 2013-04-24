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



Ext.define('YMPILogin.view._Login', {
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
		items	: [
			{
				xtype: 'button',
				height: 120,
				width: 120,
				text: 'Absensi',
				scale: 'large',
				icon: './assets/images/logoapp/absensi.png',
				iconAlign: 'top',
				
				listeners: {
					click: function() {
						console.info('Tombol ditekan');
						this.setText('I was clicked!');
					},
					mouseover: function() {
						console.info('Mouse Over');
						/*this.setIcon = './assets/images/logoapp/absensi2.png';
						if (!this.mousedOver) {
							this.mousedOver = true;
							alert('You moused over a button!\n\nI wont do this again.');
						}*/
					}
				}
			},
			{
				xtype: 'button',
				height: 120,
				width: 120,
				text: 'Absensi',
				scale: 'large',
				icon: './assets/images/logoapp/mnjjemput.png',
				iconAlign: 'top',
				
				listeners: {
					click: function() {
						console.info('Tombol ditekan');
						this.setText('I was clicked!');
					},
					mouseover: function() {
						console.info('Mouse Over');
						/*this.setIcon = './assets/images/logoapp/mnjjemput2.png';
						if (!this.mousedOver) {
							this.mousedOver = true;
							alert('You moused over a button!\n\nI wont do this again.');
						}*/
					}
				}
			},
			{
				xtype: 'button',
				height: 120,
				width: 120,
				text: 'Absensi',
				scale: 'large',
				icon: './assets/images/logoapp/mnjkar.png',
				iconAlign: 'top',
				
				listeners: {
					click: function() {
						console.info('Tombol ditekan');
						this.setText('I was clicked!');
					},
					mouseover: function() {
						console.info('Mouse Over');
						/*this.setIcon = './assets/images/logoapp/mnjkar2.png';
						if (!this.mousedOver) {
							this.mousedOver = true;
							alert('You moused over a button!\n\nI wont do this again.');
						}*/
					}
				}
			},
			{
				xtype: 'button',
				height: 120,
				width: 120,
				text: 'Absensi',
				scale: 'large',
				icon: './assets/images/logoapp/mnjrekrut.png',
				iconAlign: 'top',
				
				listeners: {
					click: function() {
						console.info('Tombol ditekan');
						this.setText('I was clicked!');
					},
					mouseover: function() {
						console.info('Mouse Over');
						/*this.setIcon = './assets/images/logoapp/mnjrekrut2.png';
						if (!this.mousedOver) {
							this.mousedOver = true;
							alert('You moused over a button!\n\nI wont do this again.');
						}*/
					}
				}
			},
			{
				xtype: 'button',
				height: 120,
				width: 120,
				text: 'Absensi',
				scale: 'large',
				icon: './assets/images/logoapp/mnjshift.png',
				iconAlign: 'top',
				
				listeners: {
					click: function() {
						console.info('Tombol ditekan');
						this.setText('I was clicked!');
					},
					mouseover: function() {
						console.info('Mouse Over');
						/*this.setIcon = './assets/images/logoapp/mnjshift2.png';
						if (!this.mousedOver) {
							this.mousedOver = true;
							alert('You moused over a button!\n\nI wont do this again.');
						}*/
					}
				}
			},
			{
				xtype: 'button',
				height: 120,
				width: 120,
				text: 'Absensi',
				scale: 'large',
				icon: './assets/images/logoapp/mnjtugas.png',
				iconAlign: 'top',
				
				listeners: {
					click: function() {
						console.info('Tombol ditekan');
						this.setText('I was clicked!');
					},
					mouseover: function() {
						console.info('Mouse Over');
						/*this.setIcon = './assets/images/logoapp/mnjtugas2.png';
						if (!this.mousedOver) {
							this.mousedOver = true;
							alert('You moused over a button!\n\nI wont do this again.');
						}*/
					}
				}
			}
		]
	}]

});
