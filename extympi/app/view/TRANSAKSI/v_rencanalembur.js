Ext.define('YMPI.view.TRANSAKSI.v_rencanalembur', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_rencanalembur'],
	
	title		: 'Rencana Lembur',
	itemId		: 'Listrencanalembur',
	alias       : 'widget.Listrencanalembur',
	store 		: 's_rencanalembur',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var nik_store = Ext.create('YMPI.store.s_karyawan',{autoLoad:true,pageSize: 3000});
		var JENISLEMBUR_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"N", "display":"LEMBUR NASIONAL"},
    	        {"value":"L", "display":"LEMBUR LIBUR"},
    	        {"value":"A", "display":"LEMBUR AGAMA"},
    	        {"value":"B", "display":"LEMBUR BIASA"}
    	    ]
    	});
		
		var ANTARJEMPUT_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"YA"},
    	        {"value":"T", "display":"TIDAK"}
    	    ]
    	});
		
		var MAKAN_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"YA"},
    	        {"value":"T", "display":"TIDAK"}
    	    ]
    	});
		
		var MAKAN_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'MAKAN_field',
			store: MAKAN_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value}',
				'</tpl>'
			),
			value : 'T',
			valueField: 'value'
		});
		
		var ANTARJEMPUT_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'ANTARJEMPUT_field',
			store: ANTARJEMPUT_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value}',
				'</tpl>'
			),
			value : 'T',
			valueField: 'value'
		});
		
		var JENISLEMBUR_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'JENISLEMBUR_field',
			store: JENISLEMBUR_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value}',
				'</tpl>'
			),
			value : 'B',
			valueField: 'value'
		});
		
		/*var TJMASUK_field = Ext.create('Ext.ux.form.DateTimeField', {
			name: 'TJMASUK',
			format: 'Y-m-d',submitFormat:'Y-m-d H:i:s'
		});*/
		var TGLMASUK_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLMASUK',
			format: 'Y-m-d'
		});
		var JAMMASUK_field = Ext.create('Ext.form.field.Time', {
			itemId : 'JAMMASUK_field',
			name: 'JAMMASUK', 
			format: 'H:i:s',
			increment:1
		});
		
		/*var TJKELUAR_field = Ext.create('Ext.ux.form.DateTimeField', {
			name: 'TJKELUAR',
			format: 'Y-m-d',submitFormat:'Y-m-d H:i:s'
		});*/
		var TGLKELUAR_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLKELUAR',
			format: 'Y-m-d'
		});
		var JAMKELUAR_field = Ext.create('Ext.form.field.Time', {
			itemId : 'JAMKELUAR_field',
			name: 'JAMKELUAR', 
			format: 'H:i:s',
			increment:1
		});
		
		var NIK = Ext.create('Ext.form.field.ComboBox', {
			allowBlank : false,
			store: 's_karyawan_byunitkerja',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			queryMode: 'local',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});
		
		var NOLEMBUR_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			readOnly : true,
			maxLength: 7 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			readOnly : true,
			maxLength: 11 /* length of column name */
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NOLEMBUR))
					{
						NOLEMBUR_field.setReadOnly(true);
						//NOURUT_field.setReadOnly(true);
					}
					else
					{
						NOLEMBUR_field.setReadOnly(false);
						//NOURUT_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NOLEMBUR) || (/^\s*$/).test(e.record.data.NOURUT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NOLEMBUR) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NOLEMBUR","NOURUT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_rencanalembur/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NOLEMBUR') === e.record.data.NOLEMBUR && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
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
				header: 'NOLEMBUR',
				dataIndex: 'NOLEMBUR',
				field: NOLEMBUR_field, hidden:true
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT'
				//field: NOURUT_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK, xtype:'templatecolumn', tpl:'{NIK} - {NAMAKAR}',width:250
			},{
				header: 'TGLMASUK',
				dataIndex: 'TGLMASUK',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLMASUK_field, 
				width: 160
			},{
				header: 'JAMMASUK',
				dataIndex: 'JAMMASUK',
				field: JAMMASUK_field, 
				width: 160
			},{
				header: 'TGLKELUAR',
				dataIndex: 'TGLKELUAR',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLKELUAR_field, 
				width: 160
			},{
				header: 'JAMKELUAR',
				dataIndex: 'JAMKELUAR',
				field: JAMKELUAR_field, 
				width: 160
			},{
				header: 'ANTARJEMPUT',
				dataIndex: 'ANTARJEMPUT',
				field: ANTARJEMPUT_field
			},{
				header: 'MAKAN',
				dataIndex: 'MAKAN',
				field: MAKAN_field
			},{
				header: 'JENISLEMBUR',
				dataIndex: 'JENISLEMBUR',
				field: JENISLEMBUR_field
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
				store: 's_rencanalembur',
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