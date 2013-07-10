Ext.define('YMPI.view.MUTASI.v_riwayatkerjaympi', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_riwayatkerjaympi'],
	
	title		: 'riwayatkerjaympi',
	itemId		: 'Listriwayatkerjaympi',
	alias       : 'widget.Listriwayatkerjaympi',
	store 		: 's_riwayatkerjaympi',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var unitkerja_store = Ext.create('YMPI.store.s_unitkerja');
		
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			readOnly: true,
			maxLength: 10 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var NAMAUNIT_field = Ext.create('Ext.form.ComboBox', {
			store: unitkerja_store,
			queryMode: 'local',
			displayField: 'NAMAUNIT',
			valueField: 'KODEUNIT'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NIK) && ! (/^\s*$/).test(e.record.data.NOURUT) ){
						NIK_field.setReadOnly(true);	
						NOURUT_field.setReadOnly(true);
					}else{
						//NIK_field.setReadOnly(false);
						NOURUT_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.NOURUT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.NOURUT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","NOURUT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_riwayatkerjaympi/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
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
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK_field
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: NOURUT_field
			},{
				header: 'NAMAUNIT',
				dataIndex: 'NAMAUNIT',
				width: 250,
				field: NAMAUNIT_field
			},{
				header: 'TGLMULAI',
				dataIndex: 'TGLMULAI',
				width: 150,
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			},{
				header: 'TGLSAMPAI',
				dataIndex: 'TGLSAMPAI',
				width: 150,
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						itemId	: 'btncreate',
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create',
						disabled: true
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
						itemId	: 'btnxexcel',
						text	: 'Export Excel',
						iconCls	: 'icon-excel',
						action	: 'xexcel',
						disabled: true
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btnxpdf',
						text	: 'Export PDF',
						iconCls	: 'icon-pdf',
						action	: 'xpdf',
						disabled: true
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btnprint',
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print',
						disabled: true
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_riwayatkerjaympi',
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