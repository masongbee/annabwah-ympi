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
            //html: '<img src="./assets/images/logoapp/'+group_icon+'.png" width="36px" height="36px" />'+'&nbsp;&nbsp;YAMAHA - Creating \'Kando\' Together ',
            html: '&nbsp;&nbsp;<font color="black">YAMAHA - Creating \'Kando\' Together</font>',
            flex: 2
        },{
			xtype: 'fieldcontainer',
			width: 400,
			layout: 'hbox',
			style: 'font-color: white;',
			items: [{
				xtype: 'component',
				contentEl: 'tick2',
				style: {
					color: 'white',
					fontWeight: 'bold',
					textAlign: 'right'
				},
				flex: 1
			}, {
				xtype: 'label',
				text: '|',
				margin: '0 5 0 5',
				style: {
					color: 'white',
					fontWeight: 'bold'
				}
			}, {
				xtype: 'label',
				text: username,
				style: {
					color: 'white',
					fontWeight: 'bold'
				},
				width: 120
			}]
		},{
        	xtype: 'component',
        	html: '<img src="./assets/images/logoapp/'+group_icon+'.png" width="44px" height="44px" style="margin-top:2px; margin-right:10px;" />'
        }];

        this.callParent();
    }
});
