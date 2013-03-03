Ext.define('YMPI.view.Header', {
    extend: 'Ext.Toolbar',
    xtype : 'pageHeader',
    
    ui   : 'sencha',
    height: 53,
    
    items: [
        {
            xtype: 'component',
            cls  : 'x-logo',
            html : 'YAMAHA - Creating \'Kando\' Together'
        }
    ]
});
