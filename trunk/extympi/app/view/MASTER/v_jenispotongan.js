Ext.define('YMPI.view.MASTER.v_jenispotongan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_jenispotongan'],
	
	title		: 'jenispotongan',
	itemId		: 'Listjenispotongan',
	alias       : 'widget.Listjenispotongan',
	store 		: 's_jenispotongan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		
		/* STORE start */
		var poscetak_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"B", "display":"Berdiri Sendiri"},
    	        {"value":"J", "display":"Jumlahan"},
    	        {"value":"L", "display":"Di Luar Tambahan"}
    	    ]
    	});
		/* STORE end */
		
		var KODEPOTONGAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 2 /* length of column name */
		});
		var POSCETAK_field = Ext.create('Ext.form.field.ComboBox', {
			store: poscetak_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.KODEPOTONGAN) ){
						
						KODEPOTONGAN_field.setReadOnly(true);
					}else{
						
						KODEPOTONGAN_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.KODEPOTONGAN) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.KODEPOTONGAN) ){
						Ext.Msg.alert('Peringatan', 'Kolom "KODEPOTONGAN" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_jenispotongan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('KODEPOTONGAN') === e.record.data.KODEPOTONGAN) {
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
				header: 'KODEPOTONGAN',
				dataIndex: 'KODEPOTONGAN',
				field: KODEPOTONGAN_field
			},{
				header: 'NAMAPOTONGAN',
				dataIndex: 'NAMAPOTONGAN',
				width: 160,
				field: {xtype: 'textfield'}
			},{
				header: 'POSCETAK',
				dataIndex: 'POSCETAK',
				width: 140,
				field: POSCETAK_field
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
				store: 's_jenispotongan',
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