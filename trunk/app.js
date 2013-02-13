Ext.application({
    name: 'YMPI',

    autoCreateViewport: true,
    
    requires: [
        'Ext.window.MessageBox',
        'Ext.grid.*',
        'Ext.ux.CheckColumn',
        'Ext.ModelManager'
    ],

    controllers: [
        'Main', 'Grade', 'UnitKerjaDanJabatan', 'UserManager'
    ],
    
    launch: function(){
        /*if (!Ext.isWebKit) {
            Ext.MessageBox.alert('WebKit Only', 'This example is currently only supported in WebKit browsers');
        }*/
    }
});
