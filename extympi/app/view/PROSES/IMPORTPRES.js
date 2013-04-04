Ext.define('YMPI.view.PROSES.IMPORTPRES', {
    extend: 'Ext.Container',
    requires: [
		'Ext.form.Panel',
		'Ext.form.field.Checkbox',
		'Ext.form.field.Text'
    ],
    alias       : 'widget.IMPORTPRESList',
	
    items: [
        {
            xtype: 'form',
            
            title: 'Import Presensi',
            frame:true,
            bodyPadding: 13,
            //height: null,
            
            defaultType: 'textfield',
            defaults: { anchor: '100%' },
            
            items: [                
                { xtype: 'filefield',allowBlank:true, fieldLabel: 'Upload File', name: 'ffile', emptyText: 'Load SQL File to import data'}
            ],
            
            buttons: [{
				text: 'Import',
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
							url: 'c_importpres/upload',
							waitMsg: 'Importing Data...',
							success: function(form, action) {
								msg('Import Success', 'Data has been imported');
								//msg('Login Success', action.response.responseText);
								redirect = 'home';
								window.location = redirect;
							}
							,
							failure: function(form, action) {
								msg('Import Failed','Data Fail');
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