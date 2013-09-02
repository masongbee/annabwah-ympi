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
		/* STORE start */
		var leveljabatan_store = Ext.create('YMPI.store.s_leveljabatan', {
			autoLoad: true
		});
		var grade_store = Ext.create('YMPI.store.s_grade', {
			autoLoad: true
		});
		/* STORE end */
		
		var IDJAB_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			minLength: 10,
			maxLength: 10
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
			allowBlank: false,
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
			forceSelection:true
		});
		var GRADE_field = Ext.create('Ext.form.ComboBox', {
			store: grade_store,
			queryMode: 'local',
			displayField: 'GRADE',
			valueField: 'GRADE'
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.KODEJAB) ){
						/* PKey tidak kosong */
						IDJAB_field.setReadOnly(true);
						KODEJAB_field.setReadOnly(true);
					}
					else
					{
						IDJAB_field.setReadOnly(false);
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
											if (record.get('IDJAB') === e.record.data.IDJAB && record.get('KODEJAB') === e.record.data.KODEJAB) {
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
				header: 'IDJAB',
				dataIndex: 'IDJAB',
				field: IDJAB_field
			},{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				width: 319,
				field: KODEJAB_field
			},{
				header: 'GRADE',
				dataIndex: 'GRADE',
				width: 319,
				field: GRADE_field
			},{
				header: 'NAMAJAB',
				dataIndex: 'NAMAJAB',
				width: 200,
				field: {
					xtype: 'textfield',
					maxLength: 40
				}
			},{
				xtype: 'checkcolumn',
				header: 'HITUNGLEMBUR',
				dataIndex: 'HITUNGLEMBUR',
				width: 120,
				field: {
					xtype: 'checkbox',
					cls: 'x-grid-checkheader-editor'
				}
			},{
				xtype: 'checkcolumn',
				header: 'KOMPENCUTI',
				dataIndex: 'KOMPENCUTI',
				width: 120,
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
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						itemId	: 'btncreate',
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create',
						disabled: true
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}]
			}),
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