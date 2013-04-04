/*Ext.define('YMPI.view.Header', {
    extend: 'Ext.Toolbar',
    xtype : 'appHeader',
    id: 'app-header',
    
    //ui   : 'sencha',
    height: 52,
    
    items: [
        {
            xtype: 'component',
            html : "<div align='left'><img src=./assets/images/logo.png style='margin: 0 15px 0 15px;float:left;' /></div>" + '<h1>YAMAHA - Creating \'Kando\' Together</h1>'
        }
    ]
});*/


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
			/*style: {
				color: '#000000',
				backgroundColor:'#eae7c4'
				//backgroundImage: url('assets/images/logo.png')
			},*/
            html: 'YAMAHA - Creating \'Kando\' Together',
            flex: 1
        }];

        this.callParent();
    }
});
