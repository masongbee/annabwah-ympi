Ext.define('YMPI.view.MASTER.v_jabatan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_jabatan'],
	
	title		: 'jabatan',
	itemId		: 'Listjabatan',
	alias       : 'widget.Listjabatan',
	store 		: 's_jabatan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var KODEJAB_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			minLength: 5,
			maxLength: 5
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.KODEJAB) ){
						/* PKey tidak kosong */
						KODEJAB_field.setReadOnly(true);
					}
					else
					{
						KODEJAB_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.KODEJAB) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.KODEJAB) ){
						Ext.Msg.alert('Peringatan', 'Kolom "KODEUNIT","KODEJAB" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_jabatan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('KODEUNIT') === e.record.data.KODEUNIT && record.get('KODEJAB') === e.record.data.KODEJAB) {
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
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				field: KODEJAB_field
			},{
				header: 'NAMAJAB',
				dataIndex: 'NAMAJAB',
				flex: 1,
				field: {
					xtype: 'textfield',
					maxLength: 40
				}
			},{
				xtype: 'checkcolumn',
				header: 'HITUNGLEMBUR',
				dataIndex: 'HITUNGLEMBUR',
				field: {
					xtype: 'checkbox',
					cls: 'x-grid-checkheader-editor'
				}
			},{
				xtype: 'checkcolumn',
				header: 'KOMPENCUTI',
				dataIndex: 'KOMPENCUTI',
				field: {
					xtype: 'checkbox',
					cls: 'x-grid-checkheader-editor'
				}
			},{
				header: 'KODEAKUN',
				dataIndex: 'KODEAKUN',
				field: {
					xtype: 'textfield',
					maxLength: 10
				}
			}
		];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [{
					itemId	: 'btncreate',
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create',
					disabled: true
				}, {
					itemId	: 'btndelete',
					text	: 'Delete',
					iconCls	: 'icon-remove',
					action	: 'delete',
					disabled: true
				}]
			},
			{
				xtype: 'pagingtoolbar',
				store: 's_jabatan',
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
    },
	
	saveData: function(){
		
	}

});