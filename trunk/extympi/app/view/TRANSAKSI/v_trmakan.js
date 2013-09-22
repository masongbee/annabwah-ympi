Ext.define('YMPI.view.TRANSAKSI.v_trmakan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_trmakan'],
	
	title		: 'trmakan',
	itemId		: 'Listtrmakan',
	alias       : 'widget.Listtrmakan',
	store 		: 's_trmakan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		/* STORE start */
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
		var fmakan_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"P", "display":"Puasa"},
    	        {"value":"R", "display":"Puasa Ramadhan"},
    	        {"value":"H", "display":"Hamil"},
    	        {"value":"M", "display":"Menstruasi"},
    	        {"value":"S", "display":"Sakit"},
    	        {"value":"L", "display":"Lembur"},
    	        {"value":"T", "display":"Tugas"}
    	    ]
    	});
		/* STORE end */
		
		var MODE_field = Ext.create('Ext.form.field.Text', {
			itemId: 'MODE_field',
			hidden: true,
			value: 'create'
		});
		var GRADE_field_temp = Ext.create('Ext.form.field.Text');
		var KODEJAB_field_temp = Ext.create('Ext.form.field.Text');
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			store: nik_store,
			queryMode: 'remote',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: false,
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
				'select': function(cb, records, e){
					var data = records[0].data;
					GRADE_field_temp.setValue(data.GRADE);
					KODEJAB_field_temp.setValue(data.KODEJAB);
				}
			}
		});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d',
			listeners: {
				'change': function(thisfield, newValue, oldValue, e){
					if ((newValue !== null) && (newValue !== '')) {
						FMAKAN_field.setReadOnly(false);
					}else{
						FMAKAN_field.reset();
						FMAKAN_field.setReadOnly(true);
					}
				}
			}
		});
		var RPTMAKAN_field = Ext.create('Ext.form.field.Number',{
			readOnly: true
		});
		var FMAKAN_field = Ext.create('Ext.form.field.ComboBox', {
			store: fmakan_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			width: 120,
			readOnly: true,
			listeners: {
				'change': function(thisfield, newValue, oldValue, e){
					var obj = new Object();
					var thisvalue = newValue;
					obj.tanggal = TANGGAL_field.getValue();
					obj.fmakan = thisvalue;
					obj.nik = NIK_field.getValue();
					obj.grade = GRADE_field_temp.getValue();
					obj.kodejab = KODEJAB_field_temp.getValue();
					var jsonData = Ext.encode(obj);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_trmakan/get_tmakan',
						params: {filter: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							var data = rs.data[0];
							if (rs.total > 0) {
								RPTMAKAN_field.setValue(data.RPTMAKAN);
							}else{
								thisfield.reset();
								RPTMAKAN_field.reset();
								Ext.Msg.alert('Peringatan', 'Tidak Tunj. Makan yang ditemukan.');
							}
							
						}
					});
				}
			}
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.TANGGAL) ){
						
						NIK_field.setReadOnly(true);	
						TANGGAL_field.setReadOnly(true);
					}else{
						
						NIK_field.setReadOnly(false);
						TANGGAL_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TANGGAL" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					e.record.data.MODE = MODE_field.getValue();
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_trmakan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK && (new Date(record.get('TANGGAL'))).format('yyyy-mm-dd') === (new Date(e.record.data.TANGGAL)).format('yyyy-mm-dd')) {
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
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				field: NIK_field
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TANGGAL_field
			},{
				header: 'FMAKAN',
				dataIndex: 'FMAKAN',
				width: 160,
				field: FMAKAN_field
			},{
				header: 'RPTMAKAN',
				dataIndex: 'RPTMAKAN',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: RPTMAKAN_field
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				field: {xtype: 'textfield'}
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
						action	: 'create',
						handler	: function(){
							MODE_field.setValue('create');
						}
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
				store: 's_trmakan',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
		
		this.on('itemdblclick', function(){
			MODE_field.setValue('update');
		});
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});