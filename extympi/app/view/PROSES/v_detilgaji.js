Ext.define('YMPI.view.PROSES.v_detilgaji', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_detilgaji'],
	
	title		: 'detilgaji',
	itemId		: 'Listdetilgaji',
	alias       : 'widget.Listdetilgaji',
	store 		: 's_detilgaji',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var BULAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 6 /* length of column name */
		});
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10 /* length of column name */
		});
		var NOREVISI_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		
		var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
				if(! (/^\s*$/).test(e.record.data.BULAN) || ! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.NOREVISI) ){
					BULAN_field.setReadOnly(true);NIK_field.setReadOnly(true);NOREVISI_field.setReadOnly(true);}
					else
					{
						BULAN_field.setReadOnly(false);NIK_field.setReadOnly(false);NOREVISI_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.NOREVISI) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.NOREVISI) ){
						Ext.Msg.alert('Peringatan', 'Kolom "BULAN","NIK","NOREVISI" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_detilgaji/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('BULAN') === e.record.data.BULAN && record.get('NIK') === e.record.data.NIK && parseFloat(record.get('NOREVISI')) === e.record.data.NOREVISI) {
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
		
		Ext.apply(this, {
			columns: [
				{
					header: 'BULAN',
					dataIndex: 'BULAN',
					field: BULAN_field
				},{
					header: 'NIK',
					dataIndex: 'NIK',
					field: NIK_field
				},{
					header: 'NOREVISI',
					dataIndex: 'NOREVISI',
					field: NOREVISI_field
				},{
					header: 'RPUPAHPOKOK',
					dataIndex: 'RPUPAHPOKOK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTANAK',
					dataIndex: 'RPTANAK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTBHS',
					dataIndex: 'RPTBHS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTHR',
					dataIndex: 'RPTHR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTISTRI',
					dataIndex: 'RPTISTRI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTJABATAN',
					dataIndex: 'RPTJABATAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTPEKERJAAN',
					dataIndex: 'RPTPEKERJAAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTSHIFT',
					dataIndex: 'RPTSHIFT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTTRANSPORT',
					dataIndex: 'RPTTRANSPORT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPBONUS',
					dataIndex: 'RPBONUS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPIDISIPLIN',
					dataIndex: 'RPIDISIPLIN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTLEMBUR',
					dataIndex: 'RPTLEMBUR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTKACAMATA',
					dataIndex: 'RPTKACAMATA',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTSIMPATI',
					dataIndex: 'RPTSIMPATI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTMAKAN',
					dataIndex: 'RPTMAKAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPPSKORSING',
					dataIndex: 'RPPSKORSING',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPPSAKITCUTI',
					dataIndex: 'RPPSAKITCUTI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPPJAMSOSTEK',
					dataIndex: 'RPPJAMSOSTEK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPPOTONGAN',
					dataIndex: 'RPPOTONGAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				},{
					header: 'RPTAMBAHAN',
					dataIndex: 'RPTAMBAHAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					},
					field: {xtype: 'numberfield'}
				}],
			plugins: [rowEditing],
			dockedItems: [
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
					store: 's_detilgaji',
					dock: 'bottom',
					displayInfo: true
				}
			]
		});
		
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