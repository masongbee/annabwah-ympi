Ext.Loader.setConfig({
    enabled : true,
    paths   : {
        MyApp : 'extympi/app'
    } 
});
Ext.application({
    name: 'YMPI',
    appFolder: 'extympi/app',
    autoCreateViewport: true,

    controllers: [
        'Main', 'Grade', 'UnitKerjaDanJabatan', 'UserManager'
    ],
    
    launch: function(){
        /*if (!Ext.isWebKit) {
            Ext.MessageBox.alert('WebKit Only', 'This example is currently only supported in WebKit browsers');
        }*/
    	console.log("This example is currently only supported in WebKit browsers");
    }
});
