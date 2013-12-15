Ext.define('YMPI.view.AKSES.GPASS', {
	extend: 'Ext.form.Panel',
	
	alias	: 'widget.v_s_gpass_form',
	
	title		: 'Ganti Password',
    bodyPadding	: 5,
    autoScroll	: true,
	frame: false,
	layout: 'column',
	
    initComponent: function(){
		var me = this;
		
		var gpass_old_password_field = Ext.create('Ext.form.field.Text', {
			name: 'OLD_PASSWORD',
			fieldLabel: 'Password Lama',
			maxLength: 50,
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER) {
						gpass_new_password_field.focus(false, true);
					}
				}
			}
		});
		var gpass_new_password_field = Ext.create('Ext.form.field.Text', {
			name: 'NEW_PASSWORD',
			fieldLabel: 'Password Baru',
			maxLength: 50,
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER) {
						me.down('#save').fireEvent('click');
					}
				}
			}
		});
		
    	Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [{
				//left column
				xtype: 'form',
				bodyStyle: 'border-width: 0px;',
				columnWidth:0.49,
				items: [
					gpass_old_password_field,gpass_new_password_field
				]
			}, {
				xtype: 'splitter',
				columnWidth:0.02
			},{
				//right column
				xtype: 'form',
				bodyStyle: 'border-width: 0px;',
				columnWidth:0.49,
				items: []
			}],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                action: 'save'
            }]
        });
        
    	this.callParent(arguments);
    }

});