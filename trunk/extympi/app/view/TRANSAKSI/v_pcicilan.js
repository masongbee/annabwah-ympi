Ext.define('YMPI.view.TRANSAKSI.v_pcicilan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_pcicilan'],
	
	title		: 'pcicilan',
	itemId		: 'Listpcicilan',
	alias       : 'widget.Listpcicilan',
	store 		: 's_pcicilan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		
		var BULAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 6 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		
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
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NOURUT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "BULAN","NOURUT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_pcicilan/save',
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
								url: 'c_pcicilan/check_upload',
								waitMsg: 'Uploading your file...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									if (obj.existsdata > 0) {
										/* data sudah pernah ditambahkan, tetapi ada confirm: Apakah akan tetap dilanjutkan? */
										Ext.MessageBox.show({
											title: 'Confirm',
											msg: obj.msg,
											width: 400,
											buttons: Ext.Msg.YESNO,
											fn: function(btn){
												if (btn == 'yes') {
													console.log('button yes');
													Ext.Ajax.request({
														method: 'POST',
														url: 'c_pcicilan/do_inject',
														params: {filename: obj.filename},
														success: function(response){
															var rs = Ext.JSON.decode(response.responseText);
															Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+rs.skeepdata+' data yang tidak tersimpan.');
															me.getStore().reload();
														}
													});
												}else{
													console.log('button no');
												}
											},
											closable:false,
											icon: Ext.Msg.QUESTION
										});
									}else{
										Ext.Ajax.request({
											method: 'POST',
											url: 'c_pcicilan/do_inject',
											params: {filename: obj.filename},
											success: function(response){
												var rs = Ext.JSON.decode(response.responseText);
												Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+rs.skeepdata+' data yang tidak tersimpan.');
												me.getStore().reload();
											}
										});
										
									}
									
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
				header: 'BULAN',
				dataIndex: 'BULAN',
				field: BULAN_field
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: NOURUT_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: {xtype: 'textfield'}
			},{
				header: 'CICILANKE',
				dataIndex: 'CICILANKE',
				field: {xtype: 'numberfield'}
			},{
				header: 'RPCICILAN',
				dataIndex: 'RPCICILAN',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'}
			},{
				header: 'LAMACICILAN',
				dataIndex: 'LAMACICILAN',
				field: {xtype: 'numberfield'}
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				field: {xtype: 'textarea'}
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME',
				field: {xtype: 'textfield'}
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
				}, '-', upload_form]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_pcicilan',
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