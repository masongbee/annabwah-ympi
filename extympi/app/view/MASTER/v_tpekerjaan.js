Ext.define('YMPI.view.MASTER.v_tpekerjaan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_tpekerjaan'],
	
	title		: 'tpekerjaan',
	itemId		: 'Listtpekerjaan',
	alias       : 'widget.Listtpekerjaan',
	store 		: 's_tpekerjaan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		
		/* STORE start */
		var grade_store = Ext.create('YMPI.store.s_grade', {
			autoLoad: true
		});
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
		var katpekerjaan_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"Berdasar Kinerja"},
    	        {"value":"B", "display":"Berhak Mendapat Susu"}
    	    ]
    	});
		/* STORE end */
		
		var filters = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: false/*,   // defaults to false (remote filtering)
			
			// Filters are most naturally placed in the column definition, but can also be
			// added here.
			filters: [{
				type: 'date',
				dataIndex: 'VALIDFROM'
			},{
				type: 'date',
				dataIndex: 'VALIDTO'
			},{
				type: 'date',
				dataIndex: 'TGLMULAI'
			},{
				type: 'date',
				dataIndex: 'TGLSAMPAI'
			},{
				type: 'string',
				dataIndex: 'NIK'
			},{
				type: 'string',
				dataIndex: 'GRADE'
			},{
				type: 'string',
				dataIndex: 'KATPEKERJAAN'
			}]*/
		};
		
		var VALIDFROM_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		var VALIDTO_field = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			format: 'Y-m-d'
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var TGLMULAI_field = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			format: 'Y-m-d'
		});
		var TGLSAMPAI_field = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			format: 'Y-m-d'
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
					GRADE_field.reset();
					KATPEKERJAAN_field.reset();
				}
			}
		});
		var GRADE_field = Ext.create('Ext.form.ComboBox', {
			store: grade_store,
			queryMode: 'local',
			displayField: 'GRADE',
			valueField: 'GRADE',
			listeners: {
				'select': function(){
					NIK_field.reset();
				}
			}
		});
		var KATPEKERJAAN_field = Ext.create('Ext.form.field.ComboBox', {
			store: katpekerjaan_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			width: 120
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
				if(! (/^\s*$/).test(e.record.data.VALIDFROM) || ! (/^\s*$/).test(e.record.data.NOURUT) ){
					VALIDFROM_field.setReadOnly(true);NOURUT_field.setReadOnly(true);}
					else
					{
						VALIDFROM_field.setReadOnly(false);NOURUT_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.VALIDFROM) || (/^\s*$/).test(e.record.data.NOURUT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					var sm = e.grid.getSelectionModel();
					
					if((/^\s*$/).test(e.record.data.VALIDFROM) ){
						Ext.Msg.alert('Peringatan', 'Kolom "VALIDFROM" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_tpekerjaan/save',
						params: {data: jsonData},
						success: function(response){
							var response_obj = Ext.decode(response.responseText);
							if (response_obj.success) {
								//success = true
								e.store.reload({
									callback: function(){
										var newRecordIndex = e.store.findBy(
											function(record, id) {
												if ((new Date(record.get('VALIDFROM'))).format('yyyy-mm-dd') === (new Date(e.record.data.VALIDFROM)).format('yyyy-mm-dd') && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
													return true;
												}
												return false;
											}
										);
										// me.grid.getView().select(recordIndex);
										me.grid.getSelectionModel().select(newRecordIndex);
									}
								});
							}else{
								//success = false
								e.store.remove(sm.getSelection());
								Ext.Msg.show({
									closable: false,
									msg: response_obj.message,
									buttons: Ext.Msg.OK,
									icon: Ext.Msg.WARNING
								});
							}
							/*e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if ((new Date(record.get('VALIDFROM'))).format('yyyy-mm-dd') === (new Date(e.record.data.VALIDFROM)).format('yyyy-mm-dd') && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
												return true;
											}
											return false;
										}
									);
									// me.grid.getView().select(recordIndex);
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});*/
						}
					});
					return true;
				}
			}
		});
		
		var validtoall_form = Ext.create('Ext.form.Panel', {
			width: 210,
			frame: false,
			bodyPadding: 0,
			
			items: [{
				xtype: 'fieldcontainer',
				layout: 'hbox',
				items: [{
					xtype: 'datefield',
					name: 'VALIDTOALL',
					allowBlank : true,
					format: 'd M, Y',
					width: 120
				},{
					xtype: 'splitter'
				},{
					xtype: 'button',
					text: 'VALIDTO All',
					handler: function(){
						var form = this.up('form').getForm();
						if(form.isValid()){
							form.submit({
								url: 'c_tpekerjaan/validtoall_update',
								waitMsg: 'Updating...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									Ext.Msg.alert('Success', 'Update All VALIDTO telah berhasil.');
									me.getStore().reload();
								},
								failure: function() {
									Ext.Msg.alert("Error", Ext.JSON.decode(this.response.responseText).msg);
								}
							});
						}
					}
				}]
			}]
		});
		
		this.columns = [
			{
				header: 'VALIDFROM',
				dataIndex: 'VALIDFROM',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: VALIDFROM_field,
				filter: {
					type: 'date'
				}
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				filter: {
					type: 'numeric'
				}
			},{
				header: 'VALIDTO',
				dataIndex: 'VALIDTO',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: VALIDTO_field,
				filter: {
					type: 'date'
				}
			},{
				header: 'TGLMULAI',
				dataIndex: 'TGLMULAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLMULAI_field,
				filter: {
					type: 'date'
				}
			},{
				header: 'TGLSAMPAI',
				dataIndex: 'TGLSAMPAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLSAMPAI_field,
				filter: {
					type: 'date'
				}
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				field: NIK_field,
				filter: {
					type: 'string'
				}
			},{
				header: 'GRADE',
				dataIndex: 'GRADE',
				width: 319,
				field: GRADE_field,
				filter: {
					type: 'string'
				}
			},{
				header: 'KATPEKERJAAN',
				dataIndex: 'KATPEKERJAAN',
				width: 219,
				field: KATPEKERJAAN_field,
				filter: {
					type: 'string'
				}
			},{
				header: 'FPENGALI',
				dataIndex: 'FPENGALI',
				field: {xtype: 'textfield'},
				filter: {
					type: 'string'
				}
			},{
				header: 'RPTPEKERJAAN',
				dataIndex: 'RPTPEKERJAAN',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'},
				filter: {
					type: 'numeric'
				}
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME',
				filter: {
					type: 'string'
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
				store: 's_tpekerjaan',
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