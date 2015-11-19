Ext.define('YMPI.view.TRANSAKSI.v_penugasankar', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_penugasankar'],
	
	title		: 'penugasankar',
	itemId		: 'Listpenugasankar',
	alias       : 'widget.Listpenugasankar',
	store 		: 's_penugasankar',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;

		/* STORE start */
		var personalia_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'NIK', type: 'string', mapping: 'NIK'},
                {name: 'NAMAKAR', type: 'string', mapping: 'NAMAKAR'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_public_function/get_personalia',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		/* STORE end */

		var NOTUGAS_field = Ext.create('Ext.form.field.Text', {
			allowBlank: false,
			maxLength: 7
		});
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			store: 's_karyawan_byunitkerja',
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
			forceSelection:true
		});
		var TGLMULAI_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLMULAI_field',
			allowBlank : true,
			format: 'Y-m-d',
			vtype: 'daterange',
			endDateField: 'TGLSAMPAI_field'
		});
		var TGLSAMPAI_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLSAMPAI_field',
			allowBlank : true,
			format: 'Y-m-d',
			vtype: 'daterange',
			startDateField: 'TGLMULAI_field',
			listeners: {
				select: function(field, value, e){
					var date1 = new Date(TGLMULAI_field.getValue());
					var date2 = new Date(value);
					LAMA_field.setValue(Math.abs(date2-date1)/86400000);
				}
			}
		});
		var LAMA_field = Ext.create('Ext.form.field.Number', {
			allowBlank : true,
			maxLength: 11,
			readOnly: true
		});
		var KOTA_field = Ext.create('Ext.form.field.Text', {
			allowBlank: true,
			maxLength: 50
		});
		var RINCIANTUGAS_field = Ext.create('Ext.form.field.Text', {
			allowBlank: true,
			maxLength: 50
		});
		var KETERANGAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank: true,
			maxLength: 50
		});
		var NIKATASAN1_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NIKATASAN1_field',
			name: 'NIKATASAN1', 
			allowBlank : true,
			store: 's_karyawan',
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			readOnly: true
		});
		var NIKPERSONALIA_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIKPERSONALIA_field',
			name: 'NIKPERSONALIA', 
			allowBlank : false,
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			store: personalia_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			readOnly : true
		});

		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NOTUGAS) || ! (/^\s*$/).test(e.record.data.NIK) ){
						NOTUGAS_field.setReadOnly(true);
						NIK_field.setReadOnly(true);
					}else{
						NOTUGAS_field.setReadOnly(false);
						NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NOTUGAS) || (/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK" tidak boleh kosong.');
						return false;
					}
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_penugasankar/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NOTUGAS') === e.record.data.NOTUGAS && parseFloat(record.get('NIK')) === e.record.data.NIK) {
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
		var upload_form = Ext.create('Ext.form.Panel', {
			width: 300,
			frame: false,
			bodyPadding: 0,
			
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
								url: 'c_penugasankar/do_upload',
								waitMsg: 'Uploading your file...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+obj.skeepdata+' data yang tidak tersimpan.');
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
				header: 'NOTUGAS',
				dataIndex: 'NOTUGAS',
				width: 100,
				field: NOTUGAS_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				renderer: function(value, metaData, record){
					return '['+record.data.NIK+'] - '+record.data.NAMAKAR;
				},
				field: NIK_field
			},{
				header: 'TGLMULAI',
				dataIndex: 'TGLMULAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLMULAI_field
			},{
				header: 'TGLSAMPAI',
				dataIndex: 'TGLSAMPAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TGLSAMPAI_field
			},{
				header: 'LAMA',
				dataIndex: 'LAMA',
				width: 60,
				field: LAMA_field
			},{
				header: 'PENGUSUL',
				dataIndex: 'NIKATASAN1',
				width: 260,
				renderer: function(value, metaData, record){
					return '['+record.data.NIKATASAN1+'] - '+record.data.NAMAKARATASAN1;
				},
				field: NIKATASAN1_field
			},{
				header: 'NIKPERSONALIA',
				dataIndex: 'NIKPERSONALIA',
				width: 260,
				renderer: function(value, metaData, record){
					return '['+record.data.NIKPERSONALIA+'] - '+record.data.NAMAKARHR;
				},
				field: NIKPERSONALIA_field
			},{
				header: 'KOTA',
				dataIndex: 'KOTA',
				width: 120,
				field: KOTA_field
			},{
				header: 'RINCIANTUGAS',
				dataIndex: 'RINCIANTUGAS',
				width: 220,
				field: RINCIANTUGAS_field
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				width: 220,
				field: KETERANGAN_field
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
				}, '-', upload_form]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_penugasankar',
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