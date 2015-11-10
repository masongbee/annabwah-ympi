Ext.define('YMPI.view.MASTER.v_jenisabsen', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_jenisabsen'],
	
	title		: 'Jenis Absen',
	itemId		: 'Listjenisabsen',
	alias       : 'widget.Listjenisabsen',
	store 		: 's_jenisabsen',
	loadMask	: true,
    plugins		: 'bufferedrenderer',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	
		var JENISABSEN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 2 /* length of column name */
		});
		var JENISABSEN_ALIAS_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			maxLength: 2 /* length of column name */
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.JENISABSEN) ){
						
						JENISABSEN_field.setReadOnly(true);
					}else{
						
						JENISABSEN_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.JENISABSEN) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.JENISABSEN) ){
						Ext.Msg.alert('Peringatan', 'Kolom "JENISABSEN" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_jenisabsen/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('JENISABSEN') === e.record.data.JENISABSEN) {
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
			{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				field: JENISABSEN_field
			},{
				header: 'JENISABSEN_ALIAS',
				dataIndex: 'JENISABSEN_ALIAS',
				field: JENISABSEN_ALIAS_field
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				width: 200,
				field: {xtype: 'textfield'}
			},{
				header: 'KELABSEN',
				dataIndex: 'KELABSEN',
				field: {xtype: 'textfield'}
			},{
				header: 'POTONG',
				dataIndex: 'POTONG',
				field: {xtype: 'textfield'}
			},{
				header: 'INSDISIPLIN',
				dataIndex: 'INSDISIPLIN',
				field: {xtype: 'textfield'}
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
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Export Excel',
						iconCls	: 'icon-excel',
						action	: 'xexcel'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Export PDF',
						iconCls	: 'icon-pdf',
						action	: 'xpdf'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_jenisabsen',
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