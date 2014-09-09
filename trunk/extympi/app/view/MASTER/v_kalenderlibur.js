Ext.define('YMPI.view.MASTER.v_kalenderlibur', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_kalenderlibur'],
	
	title		: 'Kalender Libur',
	itemId		: 'Listkalenderlibur',
	alias       : 'widget.Listkalenderlibur',
	store 		: 's_kalenderlibur',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		var agama_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"I", "display":"Islam"},
    	        {"value":"P", "display":"Kristen Protestan"},
    	        {"value":"K", "display":"Kristen Katholik"},
    	        {"value":"H", "display":"Hindu"},
    	        {"value":"B", "display":"Budha"},
    	        {"value":"C", "display":"Konghucu"}
    	    ]
    	});
		
		var jenislibur_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"N", "display":"Libur Nasional"},
    	        {"value":"A", "display":"Libur Keagamaan"},
    	        {"value":"P", "display":"Libur Pengganti"},
    	        {"value":"K", "display":"Hari Kerja"},
    	        {"value":"C", "display":"Libur Cuti"}
    	    ]
    	});
		
		var AGAMA_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'AGAMA', /* column name of table */
			store: agama_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		
		var JenisLibur_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'JENISLIBUR', /* column name of table */
			store: jenislibur_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
	
		var tglmulai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglmulai',
			fieldLabel: 'Tgl Mulai',
			labelWidth: 55,
			name: 'TGLMULAI',
			format: 'd M, Y',
			submitFormat: 'Y-m-d',
			altFormats: 'm,d,Y|Y-m-d',
			readOnly: false,
			width: 180
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglsampai',
			fieldLabel: 'Tgl Sampai',
			labelWidth: 70,
			name: 'TGLSAMPAI',
			format: 'd M, Y',
			submitFormat: 'Y-m-d',
			altFormats: 'm,d,Y|Y-m-d',
			readOnly: false,
			width: 180
		});
	
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.TANGGAL) ){
						
						TANGGAL_field.setReadOnly(true);
					}else{
						
						TANGGAL_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.TANGGAL) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.TANGGAL) ){
						Ext.Msg.alert('Peringatan', 'Kolom "TANGGAL" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_kalenderlibur/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if ((new Date(record.get('TANGGAL'))).format('yyyy-mm-dd') === (new Date(e.record.data.TANGGAL)).format('yyyy-mm-dd')) {
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
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				width: 110,
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: TANGGAL_field
			},{
				header: 'JENISLIBUR',
				dataIndex: 'JENISLIBUR',
				width: 150,
				field: JenisLibur_field,
				renderer : function(val,metadata,record) {
					if (record.data.JENISLIBUR == "N" ) {
						return "Libur Nasional";
					}
					else if (record.data.JENISLIBUR == "A" ) {
						return "Libur Keagamaan";
					}
					else if (record.data.JENISLIBUR == "P" ) {
						return "Libur Pengganti";
					}
					else if (record.data.JENISLIBUR == "K" ) {
						return "Hari Kerja";
					}
					else if (record.data.JENISLIBUR == "C" ) {
						return "Libur Cuti";
					}
					return val;
				}
			},{
				header: 'AGAMA',
				dataIndex: 'AGAMA',
				width: 190,
				field: AGAMA_field,
				renderer : function(val,metadata,record) {
					if (record.data.AGAMA == "I" ) {
						return "Islam";
					}
					else if (record.data.AGAMA == "P" ) {
						return "Kristen Protestan";
					}
					else if (record.data.AGAMA == "K" ) {
						return "Kristen Katholik";
					}
					else if (record.data.AGAMA == "H" ) {
						return "Hindu";
					}
					else if (record.data.AGAMA == "B" ) {
						return "Budha";
					}
					else if (record.data.AGAMA == "C" ) {
						return "Konghucu";
					}
					return val;
				}
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				width: 190,
				field: {xtype: 'textfield'}
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [
					tglmulai_filterField, {
						xtype: 'splitter'
					}, tglsampai_filterField, {
						xtype: 'splitter'
					},{
						text	: 'Filter',
						iconCls	: 'icon-grid',
						action	: 'filter'
					},{
						xtype: 'splitter'
					},{
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
						//action	: 'print',
						handler	: function(){
							Ext.ux.egen.Printer.mainTitle = 'Master Kalender Libur';
							Ext.ux.egen.Printer.printAuto = false;
							Ext.ux.egen.Printer.print(me);
							//Ext.ux.egen.Printer.generateBody(me);
						}
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_kalenderlibur',
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