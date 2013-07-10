Ext.define('YMPI.view.MUTASI.v_keluarga', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_keluarga'],
	
	title		: 'keluarga',
	itemId		: 'Listkeluarga',
	alias       : 'widget.Listkeluarga',
	store 		: 's_keluarga',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var STATUSKEL_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 1 /* length of column name */
		});
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			readOnly: true,
			maxLength: 10 /* length of column name */
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NOURUT) && ! (/^\s*$/).test(e.record.data.STATUSKEL) && ! (/^\s*$/).test(e.record.data.NIK) ){
						console.log('set readonly');
						NOURUT_field.setReadOnly(true);
						STATUSKEL_field.setReadOnly(true);
						NIK_field.setReadOnly(true);}
					else
					{
						NOURUT_field.setReadOnly(false);
						STATUSKEL_field.setReadOnly(false);
						//NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NOURUT) || (/^\s*$/).test(e.record.data.STATUSKEL) || (/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NOURUT) || (/^\s*$/).test(e.record.data.STATUSKEL) || (/^\s*$/).test(e.record.data.NIK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NOURUT","STATUSKEL","NIK" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_keluarga/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (parseFloat(record.get('NOURUT')) === e.record.data.NOURUT && record.get('STATUSKEL') === e.record.data.STATUSKEL && record.get('NIK') === e.record.data.NIK) {
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
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: NOURUT_field
			},{
				header: 'STATUSKEL',
				dataIndex: 'STATUSKEL',
				field: STATUSKEL_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK_field
			},{
				header: 'NAMAKEL',
				dataIndex: 'NAMAKEL',
				field: {xtype: 'textfield'}
			},{
				header: 'JENISKEL',
				dataIndex: 'JENISKEL',
				field: {xtype: 'textfield'}
			},{
				header: 'ALAMAT',
				dataIndex: 'ALAMAT',
				field: {xtype: 'textfield'}
			},{
				header: 'TMPLAHIR',
				dataIndex: 'TMPLAHIR',
				field: {xtype: 'textfield'}
			},{
				header: 'TGLLAHIR',
				dataIndex: 'TGLLAHIR',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			},{
				header: 'PENDIDIKAN',
				dataIndex: 'PENDIDIKAN',
				field: {xtype: 'textfield'}
			},{
				header: 'PEKERJAAN',
				dataIndex: 'PEKERJAAN',
				field: {xtype: 'textfield'}
			},{
				header: 'TANGGUNGSPKK',
				dataIndex: 'TANGGUNGSPKK',
				field: {xtype: 'textfield'}
			},{
				header: 'TGLMENINGGAL',
				dataIndex: 'TGLMENINGGAL',
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
				store: 's_keluarga',
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