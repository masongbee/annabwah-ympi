Ext.define('KitchenSinkLogin.controller.Main', {
    extend: 'Ext.app.Controller',
    
    views: ['Login'],
    
    init: function() {
        this.control({
        	'login button[action=loginBtn]': {
                click: this.verifyLogin
            }
        });
    },
    
    verifyLogin: function(){
    	//console.log('verifikasi login');
    	var redirect = 'welcome';
		window.location = redirect;
    }

});
