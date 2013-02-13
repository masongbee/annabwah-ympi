Ext.Loader.setConfig({
    enabled : true,
    paths   : {
        MyApp : 'loginapp'
    } 
});
Ext.application({
    name: 'KitchenSinkLogin',
    
    appFolder: 'loginapp',

    autoCreateViewport: true,

    controllers: [
        'Main'
    ],
    
    launch: function(){
        console.log("This example is currently only supported in WebKit browsers");
    }
});
