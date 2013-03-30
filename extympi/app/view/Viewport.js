Ext.define('YMPI.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires:[
        'Ext.tab.Panel',
        'Ext.layout.container.Border'
    ],

    layout: 'border',

    items: [{
        region: 'north',
        xtype: 'appHeader'
    }, {
        region: 'west',
        xtype: 'navigation',
        width: 250,
        split: true,
        stateful: true,
        stateId: 'mainnav.west',
        collapsible: true
    }, {
        region: 'center',
        layout: {
            type : 'hbox',
            align: 'stretch'
        },
        items:[{
            cls: 'x-example-panel',
            flex: 1,
            //title: '&nbsp;',
            id   : 'contentPanel',
            layout: {
                type: 'card',
                //align: 'center',
                //pack: 'center'
            },
            overflowY: 'auto',
            bodyPadding: 0
        }]
    }]
});