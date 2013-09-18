Ext.define('YMPI.view.MASTER.v_tkacamata', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_tkacamata'],
	
	title		: 'tkacamata',
	itemId		: 'Listtkacamata',
	alias       : 'widget.Listtkacamata',
	store 		: 's_tkacamata',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		/* STORE start */
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
		/* STORE end */
		
		var MODE_field = Ext.create('Ext.form.field.Text', {
			itemId: 'MODE_field',
			hidden: true,
			value: 'create'
		});
		var BULAN_field = Ext.create('Ext.form.field.Month', {
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
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.BULAN) || ! (/^\s*$/).test(e.record.data.NIK) ){
						
						BULAN_field.setReadOnly(true);	
						NIK_field.setReadOnly(true);
					}else{
						
						BULAN_field.setReadOnly(false);
						NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NIK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "BULAN","NIK" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					e.record.data.MODE = MODE_field.getValue();
					var jsonData = Ext.encode(e.record.data);
					
					/* checking data */
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_tkacamata/check',
						params: {data: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							if (rs.result == 1) {
								console.log('siap ditambahkan data');
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_tkacamata/save',
									params: {data: jsonData},
									success: function(response){
										e.store.reload({
											callback: function(){
												var newRecordIndex = e.store.findBy(
													function(record, id) {
														if (record.get('BULAN') === e.record.data.BULAN && record.get('NIK') === e.record.data.NIK) {
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
							}else{
								Ext.MessageBox.show({
									title: 'Confirm',
									msg: rs.message,
									width: 400,
									buttons: Ext.Msg.YESNO,
									fn: function(btn){
										if (btn == 'yes') {
											console.log('button yes');
											Ext.Ajax.request({
												method: 'POST',
												url: 'c_tkacamata/save',
												params: {data: jsonData},
												success: function(response){
													e.store.reload({
														callback: function(){
															var newRecordIndex = e.store.findBy(
																function(record, id) {
																	/*if (record.get('BULAN') === e.record.data.BULAN && record.get('NIK') === e.record.data.NIK) {
																		return true;
																	}*/
																	if (record === e.record.data) {
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
										}else{
											console.log('button no');
											e.store.removeAt(0);
										}
									},
									closable:false,
									icon: Ext.Msg.QUESTION
								});
							}
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
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				field: NIK_field
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			},{
				header: 'RPFRAME',
				dataIndex: 'RPFRAME',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'}
			},{
				header: 'RPLENSA',
				dataIndex: 'RPLENSA',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'}
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
				store: 's_tkacamata',
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