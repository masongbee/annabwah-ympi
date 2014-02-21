Ext.define('YMPI.view.MASTER.v_cutitahunan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_cutitahunan'],
	
	title		: 'cutitahunan',
	itemId		: 'Listcutitahunan',
	alias       : 'widget.Listcutitahunan',
	store 		: 's_cutitahunan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	selModel: {
		mode: 'MULTI'
	},
	
	initComponent: function(){
		var me = this;
		
		/* STORE start */
		var dikompensasi_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"Telah Dikompensasi"},
    	        {"value":"H", "display":"Hangus"}
    	    ]
    	});
		var jeniscuti_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"0", "display":"Cuti Tahunan"},
    	        {"value":"1", "display":"Cuti Tambahan 1"},
				{"value":"2", "display":"Cuti Tambahan 2"},
				{"value":"3", "display":"Cuti Tambahan 3"}
    	    ]
    	});
		/* STORE end */
		
		var filters = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: false   // defaults to false (remote filtering)
		};
		
		var TAHUN_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 4 /* length of column name */
		});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			store: 'YMPI.store.s_karyawan',
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
			forceSelection:true
		});
		var DIKOMPENSASI_field = Ext.create('Ext.form.field.ComboBox', {
			store: dikompensasi_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{value}</b>] - {display}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{value}] - {display}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true
		});
		var JENISCUTI_field = Ext.create('Ext.form.field.ComboBox', {
			store: jeniscuti_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{value}</b>] - {display}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{value}] - {display}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.TAHUN) || ! (/^\s*$/).test(e.record.data.TANGGAL) ){
						
						NIK_field.setReadOnly(true);	
						TAHUN_field.setReadOnly(true);	
						TANGGAL_field.setReadOnly(true);
					}else{
						
						NIK_field.setReadOnly(false);
						TAHUN_field.setReadOnly(false);
						TANGGAL_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TAHUN) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TAHUN) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TAHUN","TANGGAL" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_cutitahunan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK && parseFloat(record.get('TAHUN')) === e.record.data.TAHUN && (new Date(record.get('TANGGAL'))).format('yyyy-mm-dd') === (new Date(e.record.data.TANGGAL)).format('yyyy-mm-dd')) {
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
				field: NIK_field,
				renderer: function(value, metaData, record){
					return record.data.NIKDISPLAY;
				},
				filter: {
					type: 'string'
				}
			},{
				header: 'TAHUN',
				dataIndex: 'TAHUN',
				width: 60,
				field: TAHUN_field,
				filter: {
					type: 'numeric'
				}
			},{
				header: 'JENISCUTI',
				dataIndex: 'JENISCUTI',
				width: 150,
				field: JENISCUTI_field,
				renderer: function(value){
					index = jeniscuti_store.findExact('value',value); 
					if (index != -1){
						rs = jeniscuti_store.getAt(index).data; 
						return '['+rs.value+'] - '+rs.display; 
					}else{
						return value;
					}
				},
				filter: {
					type: 'string'
				}
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TANGGAL_field
			},{
				header: 'JMLCUTI',
				dataIndex: 'JMLCUTI',
				align: 'right',
				field: {xtype: 'numberfield'}
			},{
				header: 'SISACUTI',
				dataIndex: 'SISACUTI',
				align: 'right',
				field: {xtype: 'numberfield'}
			},{
				header: 'STATUS',
				dataIndex: 'DIKOMPENSASI',
				width: 170,
				field: DIKOMPENSASI_field,
				renderer: function(value){
					index = dikompensasi_store.findExact('value',value); 
					if (index != -1){
						rs = dikompensasi_store.getAt(index).data; 
						return '['+rs.value+'] - '+rs.display; 
					}else{
						return value;
					}
				}
			}];
		this.plugins = [this.rowEditing];
		this.features = [filters];
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
						text	: 'Generate',
						iconCls	: 'icon-plugin',
						action	: 'generate'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Hangus All',
						iconCls	: 'icon-plugin',
						action	: 'hangusall'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Kompensasi All',
						iconCls	: 'icon-plugin',
						action	: 'kompensasiall'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btnhangus',
						text	: 'Hangus',
						iconCls	: 'icon-plugin',
						action	: 'hangus',
						disabled: true
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btnkompensasi',
						text	: 'Kompensasi',
						iconCls	: 'icon-plugin',
						action	: 'kompensasi',
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
				store: 's_cutitahunan',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
		
		this.child('pagingtoolbar').add([
			'->',
			{
				text: 'Clear Filter Data',
				handler: function () {
					me.filters.clearFilters();
				} 
			}
		]);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});