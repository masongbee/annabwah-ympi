Ext.define('YMPI.view.MASTER.v_tpekerjaan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_tpekerjaan'],
	
	title		: 'tpekerjaan',
	itemId		: 'Listtpekerjaan',
	alias       : 'widget.Listtpekerjaan',
	store 		: 's_tpekerjaan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	
		var VALIDFROM_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
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
				if(! (/^\s*$/).test(e.record.data.VALIDFROM) || ! (/^\s*$/).test(e.record.data.NOURUT) ){
					VALIDFROM_field.setReadOnly(true);NOURUT_field.setReadOnly(true);}
					else
					{
						VALIDFROM_field.setReadOnly(false);NOURUT_field.setReadOnly(false);
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
					var sm = e.grid.getSelectionModel();
					
					if((/^\s*$/).test(e.record.data.VALIDFROM) || (/^\s*$/).test(e.record.data.NOURUT) ){
						Ext.Msg.alert('Peringatan', 'Kolom "VALIDFROM","NOURUT" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_tpekerjaan/save',
						params: {data: jsonData},
						success: function(response){
							var response_obj = Ext.decode(response.responseText);
							if (response_obj.success) {
								//success = true
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
										// me.grid.getView().select(recordIndex);
										me.grid.getSelectionModel().select(newRecordIndex);
									}
								});
							}else{
								//success = false
								e.store.remove(sm.getSelection());
								Ext.Msg.show({
									closable: false,
									msg: response_obj.message,
									buttons: Ext.Msg.OK,
									icon: Ext.Msg.WARNING
								});
							}
							/*e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if ((new Date(record.get('VALIDFROM'))).format('yyyy-mm-dd') === (new Date(e.record.data.VALIDFROM)).format('yyyy-mm-dd') && parseFloat(record.get('NOURUT')) === e.record.data.NOURUT) {
												return true;
											}
											return false;
										}
									);
									// me.grid.getView().select(recordIndex);
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});*/
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [
			{
				header: 'VALIDFROM',
				dataIndex: 'VALIDFROM',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: VALIDFROM_field
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT',
				field: NOURUT_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				field: {xtype: 'textfield'}
			},{
				header: 'KATPEKERJAAN',
				dataIndex: 'KATPEKERJAAN',
				field: {xtype: 'textfield'}
			},{
				header: 'RPTPEKERJAAN',
				dataIndex: 'RPTPEKERJAAN',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'}
			},{
				header: 'FPENGALI',
				dataIndex: 'FPENGALI',
				field: {xtype: 'textfield'}
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME',
				field: {xtype: 'textfield'}
			},{
				header: 'GRADE',
				dataIndex: 'GRADE',
				field: {xtype: 'textfield'}
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [{
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create'
				}, {
					itemId	: 'btndelete',
					text	: 'Delete',
					iconCls	: 'icon-remove',
					action	: 'delete',
					disabled: true
				}, '-',{
					text	: 'Export Excel',
					iconCls	: 'icon-excel',
					action	: 'xexcel'
				}, {
					text	: 'Export PDF',
					iconCls	: 'icon-pdf',
					action	: 'xpdf'
				}, {
					text	: 'Cetak',
					iconCls	: 'icon-print',
					action	: 'print'
				}]
			},
			{
				xtype: 'pagingtoolbar',
				store: 's_tpekerjaan',
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