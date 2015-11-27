Ext.define('YMPI.view.MASTER.v_mkinerja', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_mkinerja'],
	
	title		: 'mkinerja',
	itemId		: 'Listmkinerja',
	alias       : 'widget.Listmkinerja',
	store 		: 's_mkinerja',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;

		var KODE_field = Ext.create('Ext.form.field.Text', {
			allowBlank: false,
			maxLength: 7
		});
		var NAMAPENILAIAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank: true,
			maxLength: 25
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
			startDateField: 'TGLMULAI_field'
		});

		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.KODE)){
						KODE_field.setReadOnly(true);
					}else{
						KODE_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.KODE)){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.KODE) ){
						Ext.Msg.alert('Peringatan', 'Kolom "KODE" tidak boleh kosong.');
						return false;
					}
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_mkinerja/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('KODE') === e.record.data.KODE) {
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
								url: 'c_mkinerja/do_upload',
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
				header: 'KODE',
				dataIndex: 'KODE',
				width: 100,
				field: KODE_field
			},{
				header: 'NAMAPENILAIAN',
				dataIndex: 'NAMAPENILAIAN',
				width: 319,
				field: NAMAPENILAIAN_field
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
				store: 's_mkinerja',
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