Ext.define('YMPI.view.MASTER.v_upahpokok', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_upahpokok'],
	
	title		: 'upahpokok',
	itemId		: 'Listupahpokok',
	alias       	: 'widget.Listupahpokok',
	store 		: 's_upahpokok',
	columnLines 	: true,
	frame		: true,
	
	margin		: 0,
	
	initComponent: function(){
		var stgrade = Ext.create('YMPI.store.s_grade');
		
		// Create the combo box, attached to the states data store
		var cbgrade = Ext.create('Ext.form.ComboBox', {
		    store: stgrade,
		    queryMode: 'local',
		    displayField: 'KETERANGAN',
		    valueField: 'GRADE'
		});
		
		/* Primary-Key Variable start */
		var validfrom_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'm-d-Y'
		});
		var nourut_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false
		});
		/* Primary-Key Variable end */
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.VALIDFROM) || ! (/^\s*$/).test(e.record.data.NOURUT)){
						validfrom_field.setReadOnly(true);
						nourut_field.setReadOnly(true);
					}else{
						validfrom_field.setReadOnly(false);
						nourut_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.VALIDFROM) || (/^\s*$/).test(e.record.data.NOURUT)){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					if((/^\s*$/).test(e.record.data.VALIDFROM) || (/^\s*$/).test(e.record.data.NOURUT)){
						Ext.Msg.alert('Peringatan', 'Kolom "VALIDFROM","NOURUT" tidak boleh kosong.');
						return false;
					}
					e.store.sync();
					return true;
				}
			}
		});
		
		this.columns = [
			{ header: 'VALIDFROM', dataIndex: 'VALIDFROM', renderer: Ext.util.Format.dateRenderer('d M, Y'), field: validfrom_field},
			{ header: 'NOURUT', dataIndex: 'NOURUT', field: nourut_field},
			{ header: 'GRADE', dataIndex: 'GRADE', field: cbgrade },
			{ header: 'KODEJAB', dataIndex: 'KODEJAB', field: {xtype: 'textfield'} },
			{ header: 'NIK', dataIndex: 'NIK', field: {xtype: 'textfield'} },
			{
				header: 'RP.UPAHPOKOK',
				dataIndex: 'RPUPAHPOKOK',
				width: 160,
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'}
			},
			{ header: 'USERNAME', dataIndex: 'USERNAME', field: {xtype: 'textfield', readOnly: true} }];
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
				store: 's_upahpokok',
				dock: 'bottom',
				displayInfo: false
			}
		];
		
		this.callParent(arguments);
	}

});