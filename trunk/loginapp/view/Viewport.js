Ext.define('KitchenSinkLogin.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.layout.container.VBox',
        'Ext.form.Panel',
        'Ext.form.field.Checkbox',
        'Ext.form.field.Text'
    ],
    
    layout: {
        type: 'vbox',
        align: 'center',
        pack: 'center'
    },
    
    
    items: [
        {
            xtype: 'login'
        }
    ]
});
