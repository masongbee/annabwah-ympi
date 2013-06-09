var groups = Ext.create('Ext.data.Store', {
	fields: ['value', 'display'],
	data : [
	        {"value":"mnjrekrut", "display":"Manajemen Rekrutmen"},
	        {"value":"mnjkar", "display":"Manajemen Data Karyawan"},
	        {"value":"trainingdev", "display":"Training dan Development"},
	        {"value":"mnjshift", "display":"Manajemen Shift"},
	        {"value":"presensi", "display":"Presensi"},
	        {"value":"absensi", "display":"Absensi"},
	        {"value":"mnjjemput", "display":"Manajemen Jemputan"},
	        {"value":"nilaikinerja", "display":"Penilaian Kinerja"},
	        {"value":"spkk", "display":"SPKK"},
	        {"value":"mnjtugas", "display":"Manajemen Penugasan"},
	        {"value":"sistemgaji", "display":"Sistem Penggajian"},
	        {"value":"mnjuser", "display":"Manajemen User"}
	        ]
});
Ext.define('YMPILogin.view.Login', {
    extend: 'Ext.Container',
    requires: [
		'Ext.form.Panel',
		'Ext.form.field.Checkbox',
		'Ext.form.field.Text'
    ],
    
    alias	: 'widget.Login',
    
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
                { allowBlank:false, fieldLabel: 'Password', name: 'pass', emptyText: 'password', inputType: 'password',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
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
						}
					}
				},
                {
            		xtype: 'combobox',
                	name: 'group',
                	fieldLabel: 'Group',
                    store: groups,
                    queryMode: 'local',
                    displayField: 'display',
                    valueField: 'value',
                    tpl: Ext.create('Ext.XTemplate',
                    		'<tpl for=".">',
                    			'<div class="x-boundlist-item" style="height:30px;">'+
                    				'<img src="./assets/images/logoapp/{value}.png" align="left" width="25px" height="25px" style="margin-top:1px" />&nbsp;&nbsp;'+
                    				'{display}'+
                    			'</div>',
                    		'</tpl>'
                    ),
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
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
						}
					}
            	},
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