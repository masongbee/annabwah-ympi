Ext.define('YMPI.view.Header', {
    extend: 'Ext.Container',
    xtype: 'appHeader',
    id: 'app-header',
    height: 52,
    layout: {
        type: 'hbox',
        align: 'middle'
    },
    initComponent: function() {
        this.items = [{
            xtype: 'component',
            id: 'app-header-title',
            html: 'Ext JS Kitchen Sink',
            flex: 1
        }];

        this.callParent();
    }
});
