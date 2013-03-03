Ext.Loader.setConfig({
    enabled : true,
    paths   : {
        MyApp : 'extympi/applogin'
    } 
});
Ext.application({
    name: 'YMPILogin',
    
    appFolder: 'extympi/applogin',

    autoCreateViewport: true,

    controllers: [
        'Main'
    ],
    
    launch: function(){
        console.log("This example is currently only supported in WebKit browsers");
    }
});
