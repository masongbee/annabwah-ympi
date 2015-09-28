Ext.define('YMPI.view.TRANSAKSI.v_tahapseleksi', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_tahapseleksi'],
	
	title		: 'tahapseleksi',
	itemId		: 'Listtahapseleksi',
	alias       : 'widget.Listtahapseleksi',
	store 		: 's_tahapseleksi',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		/* STORE start */
		var lulus_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"P", "display":"Proses"},
    	        {"value":"Y", "display":"Lulus"},
    	        {"value":"T", "display":"Tidak Lulus"}
    	    ]
    	});
		/* STORE end */
		
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		var LULUS_field = Ext.create('Ext.form.field.ComboBox', {
			store: lulus_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			allowBlank: false
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'afteredit': function(editor, e){
					var me = this;
					
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_tahapseleksi/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('KTP') == e.record.data.KTP && parseFloat(record.get('NOURUT')) == e.record.data.NOURUT) {
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
				header: 'KTP',
				dataIndex: 'KTP',
				width: 120
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				width: 80
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				width: 120,
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TANGGAL_field
			},{
				header: 'GELLOW',
				dataIndex: 'GELLOW',
				width: 100
			},{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				width: 160
			},{
				header: 'IDJAB',
				dataIndex: 'IDJAB',
				width: 160
			},{
				header: 'KODESELEKSI',
				dataIndex: 'KODESELEKSI',
				width: 260,
				renderer: function(value, metaData, record){
					return '['+record.data.KODESELEKSI+'] - '+record.data.NAMASELEKSI;
				}
			},{
				header: 'LULUS',
				dataIndex: 'LULUS',
				width: 100,
				field: LULUS_field
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [/*{
						text	: 'Add',
						iconCls	: 'icon-add',
						disabled: true,
						action	: 'create'
					}*/]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_tahapseleksi',
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