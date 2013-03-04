Ext.Loader.setConfig({
    enabled : true,
    paths   : {
        MyApp : 'applogin'
    } 
});
Ext.application({
    name: 'YMPILogin',
    
    appFolder: 'applogin',

    autoCreateViewport: true,

    controllers: [
        'Main'
    ],
    
    launch: function(){
        console.log("This example is currently only supported in WebKit browsers");
    }
});
