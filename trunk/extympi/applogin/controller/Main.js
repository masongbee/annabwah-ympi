Ext.define('YMPILogin.controller.Main', {
    extend: 'Ext.app.Controller',
    
    views: ['Login'],
    
    refs: [{
		ref: 'login',
		selector: 'login'
	}],
    
    init: function() {
        this.control({
        	'login button[action=loginBtn]': {
                click: this.verifyLogin
            }
        });
    },
    
    verifyLogin: function(){
    	//console.log('verifikasi login');
    	var login = this.getLogin().down("form"),
			loginForm = login.getForm();
    	
    	var getuser = loginForm.findField("user").getValue();
    	var getpass = loginForm.findField("pass").getValue();
    	
    	Ext.Ajax.request({
			method: 'POST',
			url: 'login/verify',
			params: {user: getuser, pass: getpass},
			success:function(response){
				var result=eval(response.responseText);
				if(result == 1){
					var redirect = 'home';
					window.location = redirect;
				}else{
					Ext.MessageBox.show({
					   title: 'Warning',
					   msg: "User ID atau Password masih salah.",
					   buttons: Ext.MessageBox.OK,
					   animEl: 'save',
					   icon: Ext.MessageBox.WARNING
					});
				}
				
			},
			failure: function(response){
				var result=response.responseText;
				Ext.MessageBox.show({
				   title: 'Error',
				   msg: "Koneksi database server gagal, hubungi Administrator",
				   buttons: Ext.MessageBox.OK,
				   animEl: 'database',
				   icon: Ext.MessageBox.ERROR
				});
			}
		});
    }

});
