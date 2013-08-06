Ext.define('YMPI.view.TRANSAKSI.v_mohonizin', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_mohonizin'],
	
	title		: 'Permohonan Izin',
	itemId		: 'Listmohonizin',
	alias       : 'widget.Listmohonizin',
	store 		: 's_mohonizin',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){	
		var jenisabsen_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'JENISABSEN', type: 'string', mapping: 'JENISABSEN'},
                {name: 'KETERANGAN', type: 'string', mapping: 'KETERANGAN'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_mohonizin/get_jenisabsen',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		
		var nik_store = Ext.create('YMPI.store.s_karyawan');
		
		var NIK_field = Ext.create('Ext.form.field.ComboBox', {
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
	
		var NOIJIN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 7 /* length of column name */
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
				if(! (/^\s*$/).test(e.record.data.NOIJIN) ){
					NOIJIN_field.setReadOnly(true);}
					else
					{
						NOIJIN_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NOIJIN) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NOIJIN) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NOIJIN" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_mohonizin/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NOIJIN') === e.record.data.NOIJIN) {
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
				header: 'NOIJIN',
				dataIndex: 'NOIJIN',
				field: NOIJIN_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK_field, width: 250
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				field: JENISABSEN_field
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			},{
				header: 'JAMDARI',
				dataIndex: 'JAMDARI',
				field: {xtype: 'timefield',format: 'H:i:s', increment:1}
			},{
				header: 'JAMSAMPAI',
				dataIndex: 'JAMSAMPAI',
				field: {xtype: 'timefield',format: 'H:i:s', increment:1}
			},{
				header: 'KEMBALI',
				dataIndex: 'KEMBALI',
				field: {xtype: 'textfield'}
			},{
				header: 'DIAGNOSA',
				dataIndex: 'DIAGNOSA',
				field: {xtype: 'textfield'}
			},{
				header: 'TINDAKAN',
				dataIndex: 'TINDAKAN',
				field: {xtype: 'textfield'}
			},{
				header: 'ANJURAN',
				dataIndex: 'ANJURAN',
				field: {xtype: 'textfield'}
			},{
				header: 'PETUGASKLINIK',
				dataIndex: 'PETUGASKLINIK',
				field: {xtype: 'textfield'}
			},{
				header: 'NIKATASAN1',
				dataIndex: 'NIKATASAN1',
				field: {xtype: 'textfield'}
			},{
				header: 'NIKPERSONALIA',
				dataIndex: 'NIKPERSONALIA',
				field: {xtype: 'textfield'}
			},{
				header: 'NIKGA',
				dataIndex: 'NIKGA',
				field: {xtype: 'textfield'}
			},{
				header: 'NIKDRIVER',
				dataIndex: 'NIKDRIVER',
				field: {xtype: 'textfield'}
			},{
				header: 'NIKSECURITY',
				dataIndex: 'NIKSECURITY',
				field: {xtype: 'textfield'}
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME',
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
				store: 's_mohonizin',
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