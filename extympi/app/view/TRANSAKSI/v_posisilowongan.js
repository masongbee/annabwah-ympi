Ext.define('YMPI.view.TRANSAKSI.v_posisilowongan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_posisilowongan'],
	
	title		: 'posisilowongan',
	itemId		: 'Listposisilowongan',
	alias       : 'widget.Listposisilowongan',
	store 		: 's_posisilowongan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		/* STORE start */
		var gellow_store = Ext.create('YMPI.store.s_lowongan', {
			autoLoad: true
		});
		var jabatan_pure_store = Ext.create('YMPI.store.s_jabatan_pure', {
			autoLoad: true
		});
		var leveljabatan_store = Ext.create('YMPI.store.s_leveljabatan', {
			autoLoad: true
		});
		/* STORE end */
		
		var GELLOW_field = Ext.create('Ext.form.field.ComboBox', {
			store: gellow_store,
			queryMode: 'remote',
			displayField: 'GELLOW',
			valueField: 'GELLOW',
			allowBlank: false,
			tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{GELLOW}</b>] -  {KETERANGAN}</div>',
                '</tpl>'
            )
		});
		var IDJAB_field = Ext.create('Ext.form.field.ComboBox', {
			store: jabatan_pure_store,
			queryMode: 'remote',
			displayField: 'IDJAB',
			valueField: 'IDJAB',
			allowBlank: false,
			listeners: {
				select: function(combo, records){
					var namaunit_value = records[0].data.NAMAUNIT;
					NAMAUNIT_field.setValue(namaunit_value);
				}
			}
		});
		var NAMAUNIT_field = Ext.create('Ext.form.field.Text', {
			readOnly: true
		});
		var KODEJAB_field = Ext.create('Ext.form.field.ComboBox', {
			store: leveljabatan_store,
			queryMode: 'local',
			displayField: 'NAMALEVEL',
			valueField: 'KODEJAB',
			allowBlank: false,
			listeners: {
				select: function(combo, records){
					var keterangan_value = records[0].data.KETERANGAN;
					NAMAGRADE_field.setValue(keterangan_value);
				}
			}
		});
		var NAMAGRADE_field = Ext.create('Ext.form.field.Text', {
			readOnly: true
		});
		var JMLPOSISI_field = Ext.create('Ext.form.field.Number', {
			maxLength: 11
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.GELLOW) ){
						GELLOW_field.setReadOnly(true);
						IDJAB_field.setReadOnly(true);
						KODEJAB_field.setReadOnly(true);
					}else{
						GELLOW_field.setReadOnly(false);
						IDJAB_field.setReadOnly(false);
						KODEJAB_field.setReadOnly(false);
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
					if((/^\s*$/).test(e.record.data.GELLOW) && (/^\s*$/).test(e.record.data.IDJAB) && (/^\s*$/).test(e.record.data.KODEJAB) ){
						Ext.Msg.alert('Peringatan', 'Kolom "GELLOW", "IDJAB", dan "KODEJAB" tidak boleh kosong.');
						return false;
					}
					
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_posisilowongan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('GELLOW') == e.record.data.GELLOW && record.get('IDJAB') == e.record.data.IDJAB && record.get('KODEJAB') == e.record.data.KODEJAB ) {
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
				width: 120,
				field: GELLOW_field
			},{
				header: 'IDJAB',
				dataIndex: 'IDJAB',
				width: 160,
				field: IDJAB_field
			},{
				header: 'NAMAUNIT',
				dataIndex: 'NAMAUNIT',
				width: 160,
				field: NAMAUNIT_field
			},{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				width: 160,
				field: KODEJAB_field
			},{
				header: 'NAMAGRADE',
				dataIndex: 'NAMAGRADE',
				width: 160,
				field: NAMAGRADE_field
			},{
				header: 'JMLPOSISI',
				dataIndex: 'JMLPOSISI',
				field: JMLPOSISI_field
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
				store: 's_posisilowongan',
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