Ext.define('YMPI.view.TRANSAKSI.v_rinciancuti', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_rinciancuti'],
	
	title		: 'rinciancuti',
	itemId		: 'Listrinciancuti',
	alias       : 'widget.Listrinciancuti',
	store 		: 's_rinciancuti',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		var nik_store = Ext.create('YMPI.store.s_karyawan',{autoLoad:true,pageSize: 3000});	
		var STATUSCUTI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"DIAJUKAN"},
    	        {"value":"S", "display":"DISETUJUI"},
    	        {"value":"T", "display":"DITETAPKAN"},
    	        {"value":"C", "display":"DIBATALKAN"}
    	    ]
    	});
		var jenisabsen_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'JENISABSEN', type: 'string', mapping: 'JENISABSEN'},
                {name: 'KETERANGAN', type: 'string', mapping: 'KETERANGAN'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_permohonancuti/get_jenisabsen',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		
		var SISACUTI_field = Ext.create('Ext.form.field.Number',{
			itemId : 'SISARCUTI_field',
			name: 'SISA',
			//labelWidth: 50,
			flex: 1,
			maxLength : 5,
			readOnly: true,
			allowBlank: true
		});
		
		var NIK = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIK',
			allowBlank : false,
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: nik_store,
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
			displayField: 'NAMAKAR',
			valueField: 'NIK',
			enableKeyEvents: true,
			listeners: {
				'change': function(editor, e){
					if(editor.value != '')
					{
						if(editor.value != null)
						{
							var sisa=0;
							Ext.Ajax.request({
								url: 'c_rinciancuti/getSisa',
								params: {
									JENIS: 'SISACUTI',
									KOLOM: '',
									KEY: editor.value
								},
								success: function(response){
									var msg = Ext.decode(response.responseText);
									//console.info(msg);
									if(msg.data != '')
									{
										SISACUTI_field.setValue(msg.data[0].SISACUTI);
									}
									else
									{
										SISACUTI_field.setValue(sisa);
									}
								}
							});
						}
					}
				}
			}
		});
		
		var STATUSCUTI_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'STATRCUTI_field',
			store: STATUSCUTI_store,
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
			value : 'A',
			valueField: 'value'
		});
		
		var JENISABSEN_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'JENISABSEN', /* column name of table */
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: jenisabsen_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{JENISABSEN} - {KETERANGAN}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{JENISABSEN} - {KETERANGAN}',
				'</tpl>'
			),
			valueField: 'JENISABSEN',
			displayField: 'KETERANGAN',
		});
		
		var TGLMULAI_field = Ext.create('Ext.form.field.Date', {
			itemId : 'TGLMULAI_field',
			name: 'TGLSAMPAI', 
			format: 'Y-m-d'
		});
		
		var TGLSAMPAI_field = Ext.create('Ext.form.field.Date', {
			itemId : 'TGLSAMPAI_field',
			name: 'TGLSAMPAI', 
			format: 'Y-m-d'
		});
		
		var NOCUTI_field = Ext.create('Ext.form.field.Text', {
			//allowBlank : false,
			maxLength: 7 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			//allowBlank : false,
			maxLength: 11,
			readOnly : true
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NOCUTI) || ! (/^\s*$/).test(e.record.data.NOURUT) ){
						
						NOCUTI_field.setReadOnly(true);
						e.record.data.STATUSCUTI = 'A';						
						STATUSCUTI_field.setReadOnly(true);
						
						if(NIK.getValue() == user_nik)
						{
							NIK.setReadOnly(true);
							JENISABSEN_field.setReadOnly(true);
							TGLMULAI_field.setReadOnly(true);
							TGLSAMPAI_field.setReadOnly(true);
							STATUSCUTI_field.setReadOnly(false);
						}
						//console.info(e.record.data.STATUSCUTI);
					}else{
						
						NOCUTI_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NOCUTI) || (/^\s*$/).test(e.record.data.NOURUT) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
					console.info(e);
					if(e.newValues.TGLMULAI > e.newValues.TGLSAMPAI)
					{
						Ext.MessageBox.show({
							title: 'Tanggal',
							msg: 'Cek kembali TGLMULAI dan TGLSAMPAI!',
							buttons: Ext.MessageBox.OK,
							icon: Ext.MessageBox.WARNING
						});
						return false;
					}
					else
					{
						if(e.newValues.JENISABSEN == 'CT' && e.newValues.SISACUTI == 0){
							return false;
						}
						else if(e.newValues.JENISABSEN != 'CT')
							return true;
					}
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NOCUTI) || (/^\s*$/).test(e.record.data.NOURUT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NOCUTI","NOURUT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_rinciancuti/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NOCUTI') === e.record.data.NOCUTI && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
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
				header: 'NOCUTI',
				dataIndex: 'NOCUTI',
				field: NOCUTI_field, hidden:true
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: NOURUT_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK, width: 250, xtype:'templatecolumn', tpl:'{NIK} - {NAMAKAR}'
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				field: JENISABSEN_field
			},{
				header: 'LAMA',
				dataIndex: 'LAMA'
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
				header: 'SISACUTI',
				dataIndex: 'SISACUTI',
				field: SISACUTI_field
			},{
				header: 'STATUSCUTI',
				dataIndex: 'STATUSCUTI',
				field: STATUSCUTI_field
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
						itemId	: 'btnxexcel',
						text	: 'Export Excel',
						iconCls	: 'icon-excel',
						action	: 'xexcel'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btnxpdf',
						text	: 'Export PDF',
						iconCls	: 'icon-pdf',
						action	: 'xpdf'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btnprint',
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_rinciancuti',
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