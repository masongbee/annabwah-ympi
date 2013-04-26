Ext.define('YMPI.view.MASTER.GRADE', {
	extend: 'Ext.grid.Panel',
    
    title		: 'List Grade',
    itemId		: 'GradeList',
    alias       : 'widget.GradeList',
	store 		: 'grade',
    columnLines : true,
    
    frame		: true,
    
    margin		: 0,
    
    initComponent: function(){
    	var gradeField = Ext.create('Ext.form.field.Text', {
    		maxLength: 2
    	});
    	
    	/*
    	 * this.rowEditing ==> Plugin untuk Add/Edit di Dalam Row
    	 */
    	this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2,
			  clicksToMoveEditor: 1,
			  listeners: {
				  'beforeedit': function(editor, e){
					  if(e.record.data.GRADE != '00'){
						  gradeField.setReadOnly(true);
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if(e.record.data.GRADE == '00'){
						  editor.cancelEdit();
						  var sm = e.grid.getSelectionModel();
						  e.store.remove(sm.getSelection());
					  }
				  },
				  'validateedit': function(editor, e){
					  
				  },
				  'afteredit': function(editor, e){
					  if(eval(e.record.data.GRADE) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Grade\" tidak boleh \"00\".');
						  return false;
					  }
					  e.store.sync();
					  return true;
				  }
			  }
		});
    	
    	/*
    	 * this.columns ==> Untuk mendefiniskan kolom apa saja yang akan ditampilkan di View Grid
    	 */
        this.columns = [
            { 
            	header: 'Grade',
            	dataIndex: 'GRADE',
            	field: gradeField 
            }, { 
            	header: 'Keterangan', 
            	dataIndex: 'KETERANGAN', 
            	flex:1, 
            	field: {
            		xtype: 'textfield'
            	} 
            }
        ];
        this.plugins = [this.rowEditing];
        
        /*
         * this.dockedItems ==> Untuk menambahkan item yang ditempelkan di Panel Grid
         * xtype = 'toolbar' ==> dock item yang ditempelkan di Bagian Atas panel grid
         * xtype: 'pagingtoolbar' + dock: 'bottom' ==> dock item yang ditempelkan di Bagian Bawah panel grid
         */
        this.dockedItems = [
            {
            	xtype: 'toolbar',
            	frame: true,
                items: [{
                    text	: 'Add',
                    iconCls	: 'icon-add',
                    action	: 'create'
                }, {
                    itemId	: 'btndelete',
                    text	: 'Delete',
                    iconCls	: 'icon-remove',
                    action	: 'delete',
                    disabled: true
                }, '-',{
                	text	: 'Export Excel',
                    iconCls	: 'icon-excel',
                    action	: 'xexcel'
                }, {
                	text	: 'Export PDF',
                    iconCls	: 'icon-pdf',
                    action	: 'xpdf'
                }, {
                	text	: 'Cetak',
                    iconCls	: 'icon-print',
                    action	: 'print'
                }]
            },
            {
                xtype: 'pagingtoolbar',
                store: 'grade',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});