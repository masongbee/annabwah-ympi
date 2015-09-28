Ext.define('YMPI.view.TRANSAKSI.v_lowongan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_lowongan'],
	
	title		: 'lowongan',
	itemId		: 'Listlowongan',
	alias       : 'widget.Listlowongan',
	store 		: 's_lowongan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		/* STORE start */
		
		/* STORE end */
		
		var GELLOW_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 5 /* length of column name */
		});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		var KETERANGAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 255 /* length of column name */
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.GELLOW) ){
						GELLOW_field.setReadOnly(true);
					}else{
						GELLOW_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.GELLOW)){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.GELLOW) ){
						Ext.Msg.alert('Peringatan', 'Kolom "GELLOW" tidak boleh kosong.');
						return false;
					}
					
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_lowongan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (parseFloat(record.get('GELLOW')) === e.record.data.GELLOW) {
												return true;
											}
											return false;
										}
									);
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
			{
				header: 'GELLOW',
				dataIndex: 'GELLOW',
				field: GELLOW_field
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TANGGAL_field
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				width: 319,
				field: KETERANGAN_field
			}];
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
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_lowongan',
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