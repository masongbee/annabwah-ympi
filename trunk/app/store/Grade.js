Ext.define('YMPI.store.Grade', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.gradeStore',
    model	: 'YMPI.model.Grade',
    
    autoLoad	: true,
    autoSync	: false,
    
    storeId		: 'grade',
    
    pageSize	: 15, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : base_url + 'welcome/grade/getAllGrade',
		    create	: base_url + 'welcome/grade/saveGrade',
		    update	: base_url + 'welcome/grade/saveGrade',
		    destroy	: base_url + 'welcome/grade/deleteGrade'
        },
        actionMethods: {
		    read    : 'POST',
		    create	: 'POST',
		    update	: 'POST',
		    destroy	: 'POST'
		},
        reader: {
        	type            : 'json',
            root            : 'data',
            rootProperty    : 'data',
            successProperty : 'success',
            messageProperty : 'message'
        },
        writer: {
        	type            : 'json',
            writeAllFields  : true,
            root            : 'data',
            encode          : true
        },
        listeners: {
            exception: function(proxy, response, operation){
                Ext.MessageBox.show({
                    title: 'REMOTE EXCEPTION',
                    msg: operation.getError(),
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        }
    },
    
    constructor: function(){
    	this.callParent(arguments);
    }
    
});
