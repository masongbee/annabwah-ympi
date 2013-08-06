Ext.define('YMPI.view.TRANSAKSI.v_presensilembur', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_presensilembur'],
	
	title		: 'presensilembur',
	itemId		: 'Listpresensilembur',
	alias       : 'widget.Listpresensilembur',
	store 		: 's_presensilembur',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10
			/*listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER) {
						this.up().up().down('button[action=save]').fireEvent('click');
						console.info("Enter");
					}
				}
			}*/
		});
		var TJMASUK_field = Ext.create('Ext.form.field.Date', {
			//allowBlank : false,
			format: 'Y-m-d H:i:s'
			//value: Ext.Date.now()
		});
		
		var btn_create = Ext.create('Ext.Button', {
			text : 'Add',
			iconCls : 'icon-add',
			action : 'create'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.TJMASUK) ){
						
						NIK_field.setReadOnly(true);	
						TJMASUK_field.setReadOnly(true);
					}else{
						
						NIK_field.setReadOnly(false);
						//e.record.data.TJMASUK = Ext.Date.format(Ext.Date.now(),'Y-m-d H:i:s');
						//console.info(TJMASUK_field);Ext.Date.now()
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TJMASUK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK)){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TJMASUK" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_presensilembur/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK && (new Date(record.get('TJMASUK'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(e.record.data.TJMASUK)).format('yyyy-mm-dd hh:nn:ss')) {
												return true;
											}
											return false;
										}
									);
									/* me.grid.getView().select(recordIndex); */
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
							//btn_create.fireEvent('click');
						}
					});
					me.getView().on('refresh', this.refreshSelection, this);
					btn_create.fireEvent('click');
					return true;
				}
			}
		});
		
		this.columns = [
			{
				header: 'NIK',
				dataIndex: 'NIK',
				field: NIK_field
			},{
				header: 'NAMA',
				dataIndex: 'NAMA',
				width: 200
			},{
				header: 'TJMASUK',
				dataIndex: 'TJMASUK',
				//renderer: Ext.util.Format.dateRenderer('d M, Y'),
				//field: TJMASUK_field,
				width: 150
			},{
				header: 'NOLEMBUR',
				dataIndex: 'NOLEMBUR',
				field: {xtype: 'textfield'}
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: {xtype: 'numberfield'}
			},{
				header: 'JENISLEMBUR',
				dataIndex: 'JENISLEMBUR',
				field: {xtype: 'textfield'}
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [btn_create, {
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
				store: 's_presensilembur',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		//this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});