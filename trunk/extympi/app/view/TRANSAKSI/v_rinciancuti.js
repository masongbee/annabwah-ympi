Ext.define('YMPI.view.TRANSAKSI.v_rinciancuti', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_rinciancuti'],
	
	title		: 'Rincian Cuti',
	itemId		: 'Listrinciancuti',
	alias       : 'widget.Listrinciancuti',
	store 		: 's_rinciancuti',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){		
		var nik_store = Ext.create('YMPI.store.s_karyawan');	
		var jenisabsen_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'JENISABSEN', type: 'string', mapping: 'JENISABSEN'},
                {name: 'KETERANGAN', type: 'string', mapping: 'KETERANGAN'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_mohoncuti/get_jenisabsen',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		
		var NIK = Ext.create('Ext.form.field.ComboBox', {
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK'
		});
		
		var JENISABSEN_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'JENISABSEN', /* column name of table */
			store: jenisabsen_store,
			queryMode: 'local',
			displayField: 'KETERANGAN',
			valueField: 'JENISABSEN'
		});
	
		var NOCUTI_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 7 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		
		
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NOCUTI))
					{
						NOCUTI_field.setReadOnly(true);
					}
					else
					{
						NOCUTI_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NOCUTI) || (/^\s*$/).test(e.record.data.NOURUT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NOCUTI) || (/^\s*$/).test(e.record.data.NOURUT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NOCUTI","NOURUT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_rinciancuti/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NOCUTI') === e.record.data.NOCUTI && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
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
				header: 'NOCUTI',
				dataIndex: 'NOCUTI',
				field: NOCUTI_field
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: NOURUT_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK, width: 250
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				field: JENISABSEN_field
			},{
				header: 'LAMA',
				dataIndex: 'LAMA',
				field: {xtype: 'numberfield'}
			},{
				header: 'TGLMULAI',
				dataIndex: 'TGLMULAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'Y-m-d'}
			},{
				header: 'TGLSAMPAI',
				dataIndex: 'TGLSAMPAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'Y-m-d'}
			},{
				header: 'SISACUTI',
				dataIndex: 'SISACUTI',
				field: {xtype: 'numberfield'}
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
				store: 's_rinciancuti',
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