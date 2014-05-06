Ext.define('YMPI.view.MASTER.v_tbhs', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_tbhs'],
	
	title		: 'tbhs',
	itemId		: 'Listtbhs',
	alias       : 'widget.Listtbhs',
	store 		: 's_tbhs',
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
		var jabatan_store = Ext.create('YMPI.store.s_jabatan', {
			autoLoad: true
		});
		var bhsjepang_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"A"},
    	        {"value":"B", "display":"B"},
    	        {"value":"C", "display":"C"},
    	        {"value":"D", "display":"D"},
    	        {"value":"E", "display":"E"},
    	        {"value":"F", "display":"F"},
    	        {"value":"G", "display":"G"},
    	        {"value":"H", "display":"H"},
    	        {"value":"I", "display":"I"},
    	        {"value":"J", "display":"J"},
    	        {"value":"K", "display":"K"},
    	        {"value":"L", "display":"L"},
    	        {"value":"M", "display":"M"},
    	        {"value":"N", "display":"N"},
    	        {"value":"O", "display":"O"},
    	        {"value":"P", "display":"P"}
    	    ]
    	});
		/* STORE end */
		
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
			maxLength: 11 /* length of column name */
		});
		var GRADE_field = Ext.create('Ext.form.ComboBox', {
			store: grade_store,
			queryMode: 'local',
			displayField: 'GRADE',
			valueField: 'GRADE'
		});
		var KODEJAB_field = Ext.create('Ext.form.ComboBox', {
			store: jabatan_store,
			queryMode: 'local',
			displayField:'NAMAJAB',
			valueField: 'KODEJAB',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{KODEJAB}</b>] - {NAMAJAB}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{KODEJAB}] - {NAMAJAB}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true
		});
		var BHSJEPANG_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'BHSJEPANG', /* column name of table */
			store: bhsjepang_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			width: 120
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.VALIDFROM) || ! (/^\s*$/).test(e.record.data.NOURUT) ){
						
						VALIDFROM_field.setReadOnly(true);	
						NOURUT_field.setReadOnly(true);
					}else{
						
						VALIDFROM_field.setReadOnly(false);
						NOURUT_field.setReadOnly(false);
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
					/*if((/^\s*$/).test(e.record.data.VALIDFROM) || (/^\s*$/).test(e.record.data.NOURUT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "VALIDFROM","NOURUT" tidak boleh kosong.');
						return false;
					}*/
					if((/^\s*$/).test(e.record.data.VALIDFROM)){
						Ext.Msg.alert('Peringatan', 'Kolom "VALIDFROM" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_tbhs/save',
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
								url: 'c_tbhs/validtoall_update',
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
				field: VALIDFROM_field
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT'
			},{
				header: 'VALIDTO',
				dataIndex: 'VALIDTO',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: VALIDTO_field
			},{
				header: 'BHSJEPANG',
				dataIndex: 'BHSJEPANG',
				width: 319,
				field: BHSJEPANG_field
			},{
				header: 'GRADE',
				dataIndex: 'GRADE',
				width: 319,
				field: GRADE_field
			},{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				width: 319,
				field: KODEJAB_field
			},{
				header: 'RPTBHS',
				dataIndex: 'RPTBHS',
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
				}, '-', validtoall_form]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_tbhs',
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