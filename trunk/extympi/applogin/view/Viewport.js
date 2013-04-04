Ext.define('YMPILogin.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.layout.container.VBox',
        'Ext.form.Panel',
        'Ext.form.field.Checkbox',
        'Ext.form.field.Text'
    ],
    
    layout: {
        type: 'vbox',
        align: 'left',
        defaultMargins: {top:250, right:0, bottom:0, left:50}
    },
    
    
    items: [
        {
            xtype: 'login'
        }
    ]
});
