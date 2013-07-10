Ext.define('YMPI.view.MUTASI.v_riwayatsehat', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_riwayatsehat'],
	
	title		: 'riwayatsehat',
	itemId		: 'Listriwayatsehat',
	alias       : 'widget.Listriwayatsehat',
	store 		: 's_riwayatsehat',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var JENISSAKIT_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 1 /* length of column name */
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.NOURUT) || ! (/^\s*$/).test(e.record.data.JENISSAKIT) ){
						
						NIK_field.setReadOnly(true);	
						NOURUT_field.setReadOnly(true);	
						JENISSAKIT_field.setReadOnly(true);
					}else{
						
						NIK_field.setReadOnly(false);
						NOURUT_field.setReadOnly(false);
						JENISSAKIT_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.NOURUT) || (/^\s*$/).test(e.record.data.JENISSAKIT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.NOURUT) || (/^\s*$/).test(e.record.data.JENISSAKIT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","NOURUT","JENISSAKIT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_riwayatsehat/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT && record.get('JENISSAKIT') === e.record.data.JENISSAKIT) {
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
				header: 'JENISSAKIT',
				dataIndex: 'JENISSAKIT',
				field: JENISSAKIT_field
			},{
				header: 'RINCIAN',
				dataIndex: 'RINCIAN',
				field: {xtype: 'textarea'}
			},{
				header: 'LAMA',
				dataIndex: 'LAMA',
				field: {xtype: 'numberfield'}
			},{
				header: 'TGLRAWAT',
				dataIndex: 'TGLRAWAT',
				field: {xtype: 'textfield'}
			},{
				header: 'AKIBAT',
				dataIndex: 'AKIBAT',
				field: {xtype: 'textarea'}
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
				store: 's_riwayatsehat',
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