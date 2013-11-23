Ext.define('YMPI.view.MASTER.v_grade', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_grade'],
	
	title		: 'grade',
	itemId		: 'Listgrade',
	alias       : 'widget.Listgrade',
	store 		: 's_grade',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	
	initComponent: function(){
		var gradeField = Ext.create('Ext.form.field.Text', {
            allowBlank : false
        });
        
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(e.record.data.GRADE != ''){
						gradeField.setReadOnly(true);
					}
					
				},
				'canceledit': function(editor, e){
					/*if(e.record.data.GRADE == ''){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}*/
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					/*if((e.record.data.GRADE) == ''){
						Ext.Msg.alert('Peringatan', 'Kolom "GRADE" tidak boleh kosong.');
						return false;
					}*/
					e.store.sync();
					return true;
				}
			}
		});
		
		this.columns = [
			{ header: 'GRADE', dataIndex: 'GRADE', field: gradeField },
            { header: 'KETERANGAN', dataIndex: 'KETERANGAN', field: {xtype: 'textarea'}}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
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
					}]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
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
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_grade',
				dock: 'bottom',
				displayInfo: false
			}
		];
		this.callParent(arguments);
	}

});