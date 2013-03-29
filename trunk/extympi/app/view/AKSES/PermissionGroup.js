Ext.define('YMPI.view.AKSES.PermissionGroup', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.PermissionGroup'],
    
    title		: 'Permission',
    itemId		: 'PermissionGroup',
    alias       : 'widget.PermissionGroup',
	store 		: 'PermissionGroup',
    columnLines : true,
    region		: 'center',
    
    frame		: true,
    
    margins		: 0,
    
    initComponent: function(){
    	/*
    	 * Bisa menggunakan ==# var rowEditing #== atau ==# this.rowEditing #==
    	 */
    	/*var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2
		});*/
    	
        this.columns = [
            {
            	header: 'Nama Menu', 
            	flex: 1, 
            	dataIndex: 'TREE_MENU_TITLE',
            	renderer: function(v,params,record){
    				if(record.data.DEPTH==0)
    					return '<b><font size=\'medium\'>'+record.data.TREE_MENU_TITLE+'</font></b>';
    				else
    					return record.data.TREE_MENU_TITLE;
    			}
            },
            {
	            xtype: 'checkcolumn',
	            header: 'Hak Akses?',
	            dataIndex: 'PERM_PRIV',
	            width: 80,
	            renderer: function(value,params,record){
					if(record.data.DEPTH==0){
						return '';
					}else{
						var cssPrefix = Ext.baseCSSPrefix,
				            cls = [cssPrefix + 'grid-checkheader'];
		
				        if (value) {
				            cls.push(cssPrefix + 'grid-checkheader-checked');
				        }
				        return '<div class="' + cls.join(' ') + '">&#160;</div>';
					}
				}
	        }
        ];
        this.dockedItems = [
            {
            	xtype: 'toolbar',
            	frame: true,
                items: [{
                	itemId	: 'btnsave',
                    text	: 'Save',
                    iconCls	: 'icon-save',
                    action	: 'save',
                    disabled: true
                }]
            }
        ];
        
        this.callParent(arguments);
    }

});