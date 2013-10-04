Ext.define('YMPI.view.TRANSAKSI.v_thr', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_thr'],
	
	title		: 'thr',
	itemId		: 'Listthr',
	alias       : 'widget.Listthr',
	store 		: 's_thr',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		/* STORE start */
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
		var upengali_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"Upah Pokok"},
    	        {"value":"B", "display":"Upah Pokok + Tunj. Jabatan"},
    	        {"value":"C", "display":"Upah Pokok + Tunj. Tetap"}
    	    ]
    	});
		/* STORE end */
		
		var BULAN_field = Ext.create('Ext.form.field.Month', {
			allowBlank : false,
			format: 'M, Y'
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			store: nik_store,
			queryMode: 'remote',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				'select': function(){
					MSKERJADARI_field.reset();
					MSKERJASAMPAI_field.reset();
					PEMBAGI_field.reset();
					PENGALI_field.reset();
					UPENGALI_field.reset();
				}
			}
		});
		var MSKERJADARI_field = Ext.create('Ext.form.field.Number',{
			enableKeyEvents: true,
			listeners: {
				'spin': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				},
				'keypress': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				}
			}
		});
		var MSKERJASAMPAI_field = Ext.create('Ext.form.field.Number',{
			enableKeyEvents: true,
			listeners: {
				'spin': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				},
				'keypress': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				}
			}
		});
		var PEMBAGI_field = Ext.create('Ext.form.field.Number',{
			enableKeyEvents: true,
			listeners: {
				'spin': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				},
				'keypress': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				}
			}
		});
		var PENGALI_field = Ext.create('Ext.form.field.Number',{
			enableKeyEvents: true,
			listeners: {
				'spin': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				},
				'keypress': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				}
			}
		});
		var UPENGALI_field = Ext.create('Ext.form.field.ComboBox', {
			store: upengali_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			editable: false,
			listeners: {
				'select': function(){
					NIK_field.reset();
					RPTHR_field.reset();
				}
			}
		});
		var TGLCUTOFF_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		var RPTHR_field = Ext.create('Ext.form.field.Number');
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.BULAN) || ! (/^\s*$/).test(e.record.data.NOURUT) ){
						BULAN_field.setReadOnly(true);	
						NOURUT_field.setReadOnly(true);
					}else{
						BULAN_field.setReadOnly(false);
						NOURUT_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NOURUT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.BULAN) ){
						Ext.Msg.alert('Peringatan', 'Kolom "BULAN" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_thr/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('BULAN') === e.record.data.BULAN && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
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
				header: 'BULAN',
				dataIndex: 'BULAN',
				width: 120,
				renderer: Ext.util.Format.dateRenderer('M, Y'),
				field: BULAN_field
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT'
			},{
				header: 'TGLCUTOFF',
				dataIndex: 'TGLCUTOFF',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLCUTOFF_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				field: NIK_field
			},{
				header: 'MSKERJADARI',
				dataIndex: 'MSKERJADARI',
				width: 120,
				field: MSKERJADARI_field
			},{
				header: 'MSKERJASAMPAI',
				dataIndex: 'MSKERJASAMPAI',
				width: 120,
				field: MSKERJASAMPAI_field
			},{
				header: 'PEMBAGI',
				dataIndex: 'PEMBAGI',
				field: PEMBAGI_field
			},{
				header: 'PENGALI',
				dataIndex: 'PENGALI',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 1);
				},
				field: PENGALI_field
			},{
				header: 'UPENGALI',
				dataIndex: 'UPENGALI',
				width: 180,
				field: UPENGALI_field
			},{
				header: 'RPTHR',
				dataIndex: 'RPTHR',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: RPTHR_field
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME'
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
				store: 's_thr',
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