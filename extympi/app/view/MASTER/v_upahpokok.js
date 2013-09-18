Ext.define('YMPI.view.MASTER.v_upahpokok', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_upahpokok'],
	
	title		: 'upahpokok',
	itemId		: 'Listupahpokok',
	alias       : 'widget.Listupahpokok',
	store 		: 's_upahpokok',
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
		var leveljabatan_store = Ext.create('YMPI.store.s_leveljabatan', {
			autoLoad: true
		});
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
		/* STORE end */
		
		var filters = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: false   // defaults to false (remote filtering)
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
			readOnly: true,
			maxLength: 11 /* length of column name */
		});
		var BULANMULAI_field = Ext.create('Ext.form.field.Month', {
			allowBlank : false,
			format: 'M, Y'
		});
		var BULANSAMPAI_field = Ext.create('Ext.form.field.Month', {
			allowBlank : false,
			format: 'M, Y'
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
					KODEJAB_field.reset();
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
		var KODEJAB_field = Ext.create('Ext.form.ComboBox', {
			store: leveljabatan_store,
			queryMode: 'local',
			displayField:'NAMALEVEL',
			valueField: 'KODEJAB',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{KODEJAB}</b>] - {NAMALEVEL}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{KODEJAB}] - {NAMALEVEL}',
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
					NIK_field.reset();
				}
			}
		});
		var VALIDTOALL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			format: 'Y-m-d'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.VALIDFROM) || ! (/^\s*$/).test(e.record.data.NOURUT) ){
						VALIDFROM_field.setReadOnly(true);	
					}else{
						VALIDFROM_field.setReadOnly(false);
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
					if((/^\s*$/).test(e.record.data.VALIDFROM)){
						Ext.Msg.alert('Peringatan', 'Kolom "VALIDFROM" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_upahpokok/save',
						params: {data: jsonData},
						success: function(response){
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
		
		var upload_form = Ext.create('Ext.form.Panel', {
			width: 300,
			frame: false,
			bodyPadding: 0,
			
			/*defaults: {
				anchor: '100%',
				allowBlank: false,
				msgTarget: 'side',
				labelWidth: 70
			},*/
			
			items: [{
				xtype: 'fieldcontainer',
				layout: 'hbox',
				items: [{
					xtype: 'filefield',
					emptyText: 'Select a file to upload',
					name: 'userfile',
					width: 220
				},{
					xtype: 'splitter'
				},{
					xtype: 'button',
					text: 'Upload',
					handler: function(){
						var form = this.up('form').getForm();
						if(form.isValid()){
							form.submit({
								url: 'c_upahpokok/do_upload',
								waitMsg: 'Uploading your file...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									if (obj.skeepdata == 0) {
										Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil.');
									}else{
										Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+obj.skeepdata+' data yang tidak tersimpan.');
									}
									me.getStore().reload();
								},
								failure: function() {
									Ext.Msg.alert("Error", Ext.JSON.decode(this.response.responseText).msg);
								}
							});
						}
					}
				}]
			}]/*,
			
			buttons: [{
				text: 'Save',
				handler: function(){
					var form = this.up('form').getForm();
					if(form.isValid()){
						form.submit({
							url: 'file-upload.php',
							waitMsg: 'Uploading your photo...',
							success: function(fp, o) {
								msg('Success', 'Processed file "' + o.result.file + '" on the server');
							},
							failure: function() {
								Ext.Msg.alert("Error", Ext.JSON.decode(this.response.responseText).message);
							}
						});
					}
				}
			},{
				text: 'Reset',
				handler: function() {
					this.up('form').getForm().reset();
				}
			}]*/
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
								url: 'c_upahpokok/validtoall_update',
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
				width: 80,
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
				header: 'BULANMULAI',
				dataIndex: 'BULANMULAI',
				width: 120,
				renderer: Ext.util.Format.dateRenderer('M, Y'),
				field: BULANMULAI_field,
				filter: {
					type: 'numeric'
				}
			},{
				header: 'BULANSAMPAI',
				dataIndex: 'BULANSAMPAI',
				width: 120,
				renderer: Ext.util.Format.dateRenderer('M, Y'),
				field: BULANSAMPAI_field,
				filter: {
					type: 'numeric'
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
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				width: 319,
				field: KODEJAB_field,
				filter: {
					type: 'string'
				}
			},{
				header: 'RPUPAHPOKOK',
				dataIndex: 'RPUPAHPOKOK',
				align: 'right',
				renderer: function(value){
					//return Ext.util.Format.currency(value, 'Rp ', 2);
					return Ext.util.Format.currency(value, '&nbsp;', 2);
				},
				width: 130,
				field: {xtype: 'numberfield'}
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME'
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
				}, '-', validtoall_form, '-', upload_form]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_upahpokok',
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