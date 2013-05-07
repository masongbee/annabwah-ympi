Ext.define('YMPI.view.MASTER.v_cutitahunan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_cutitahunan'],
	
	title		: 'cutitahunan',
	itemId		: 'Listcutitahunan',
	alias       : 'widget.Listcutitahunan',
	store 		: 's_cutitahunan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	
	initComponent: function(){
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
				if(e.record.data.NIK != '' || eval(e.record.data.TAHUN) != 0 || e.record.data.TANGGAL != '0000-00-00' ){
						//cutitahunanField.setReadOnly(true);
						console.info("Before Edit Clicked....!!!");
					}
					
				},
				'canceledit': function(editor, e){
					if(e.record.data.NIK != '' || eval(e.record.data.TAHUN) != 0 || e.record.data.TANGGAL != '0000-00-00' ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					if(e.record.data.NIK == '' || eval(e.record.data.TAHUN) == 0 || e.record.data.TANGGAL == '0000-00-00' ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TAHUN","TANGGAL" tidak boleh kosong.');
						return false;
					}
					e.store.sync();
					return true;
				}
			}
		});
		
		this.columns = [
			{ header: 'NIK', dataIndex: 'NIK', field: {xtype: 'textfield', allowBlank : false} },{ header: 'TAHUN', dataIndex: 'TAHUN', field: {xtype: 'numberfield', allowBlank : false}},{ header: 'TANGGAL', dataIndex: 'TANGGAL', field: {xtype: 'datefield', allowBlank : false, format: 'm-d-Y'}},{ header: 'JENISCUTI', dataIndex: 'JENISCUTI', field: {xtype: 'textfield'} },{ header: 'JMLCUTI', dataIndex: 'JMLCUTI', field: {xtype: 'numberfield'}},{ header: 'SISACUTI', dataIndex: 'SISACUTI', field: {xtype: 'numberfield'}},{ header: 'DIKOMPENSASI', dataIndex: 'DIKOMPENSASI', field: {xtype: 'textfield'} },{ header: 'USERNAME', dataIndex: 'USERNAME', field: {xtype: 'textfield'} }];
		this.plugins = [this.rowEditing];
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
				store: 's_cutitahunan',
				dock: 'bottom',
				displayInfo: false
			}
		];
		this.callParent(arguments);
	}

});