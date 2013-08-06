Ext.define('YMPI.view.AKSES.LOGOUT', {
    extend: 'Ext.Container',
    requires: [
		'Ext.form.Panel',
		'Ext.form.field.Checkbox',
		'Ext.form.field.Text'
    ],
    alias: 'widget.logoutList',
	
    items: [
        {
            xtype: 'form',
            
            title: 'Logout...!!!',
            frame:true,
            bodyPadding: 13,
			
            buttons: [{
				text: 'Logout',
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
							url: 'c_action/logout',
							waitMsg: 'Logging Out...',
							success: function(form, action) {
								redirect = 'home';
								window.location = redirect;
							}
						});
					}
				}
			}]
        }
    ]
});