Ext.define('YMPI.view.MASTER.v_unitkerja', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_unitkerja'],
	
	title		: 'unitkerja',
	itemId		: 'Listunitkerja',
	alias       : 'widget.Listunitkerja',
	store 		: 's_unitkerja',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var KODEUNIT_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			minLength: 5,
			maxLength: 5
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					e.record.data.NAMAUNIT_TREE = e.record.data.NAMAUNIT_TREE.replace(/&nbsp;/g,'');
					if(! (/^\s*$/).test(e.record.data.KODEUNIT) ){
						KODEUNIT_field.setReadOnly(true);
					}
					else
					{
						KODEUNIT_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.KODEUNIT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.KODEUNIT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "KODEUNIT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_unitkerja/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('KODEUNIT') === e.record.data.KODEUNIT) {
												return true;
											}
											return false;
										}
									);
									/* me.grid.getView().select(recordIndex); */
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [
			{ header: 'Kode', dataIndex: 'KODEUNIT', field: KODEUNIT_field },
            { header: 'Nama', dataIndex: 'NAMAUNIT_TREE', /*flex:1, */ width: 250, editor: {xtype: 'textfield'} }
		];
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
				store: 's_unitkerja',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});