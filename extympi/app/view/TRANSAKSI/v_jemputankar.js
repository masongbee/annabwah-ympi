Ext.define('YMPI.view.TRANSAKSI.v_jemputankar', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_jemputankar'],
	
	title		: 'jemputankar',
	itemId		: 'Listjemputankar',
	alias       : 'widget.Listjemputankar',
	store 		: 's_jemputankar',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	
		var NAMASHIFT_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 20 /* length of column name */
		});
		var SHIFTKE_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 1 /* length of column name */
		});
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10 /* length of column name */
		});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NAMASHIFT) || ! (/^\s*$/).test(e.record.data.SHIFTKE) || ! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.TANGGAL) ){
						
						NAMASHIFT_field.setReadOnly(true);	
						SHIFTKE_field.setReadOnly(true);	
						NIK_field.setReadOnly(true);	
						TANGGAL_field.setReadOnly(true);
					}else{
						
						NAMASHIFT_field.setReadOnly(false);
						SHIFTKE_field.setReadOnly(false);
						NIK_field.setReadOnly(false);
						TANGGAL_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NAMASHIFT) || (/^\s*$/).test(e.record.data.SHIFTKE) || (/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NAMASHIFT) || (/^\s*$/).test(e.record.data.SHIFTKE) || (/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NAMASHIFT","SHIFTKE","NIK","TANGGAL" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_jemputankar/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NAMASHIFT') === e.record.data.NAMASHIFT && record.get('SHIFTKE') === e.record.data.SHIFTKE && record.get('NIK') === e.record.data.NIK && (new Date(record.get('TANGGAL'))).format('yyyy-mm-dd') === (new Date(e.record.data.TANGGAL)).format('yyyy-mm-dd')) {
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
				header: 'NAMASHIFT',
				dataIndex: 'NAMASHIFT',
				field: NAMASHIFT_field
			},{
				header: 'SHIFTKE',
				dataIndex: 'SHIFTKE',
				field: SHIFTKE_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK_field
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TANGGAL_field
			},{
				header: 'ZONA',
				dataIndex: 'ZONA',
				field: {xtype: 'textfield'}
			},{
				header: 'IKUTJEMPUTAN',
				dataIndex: 'IKUTJEMPUTAN',
				field: {xtype: 'textfield'}
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
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
				store: 's_jemputankar',
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