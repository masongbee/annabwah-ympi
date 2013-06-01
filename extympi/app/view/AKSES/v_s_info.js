Ext.define('YMPI.view.AKSES.v_s_info', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_s_info'],
	
	title		: 'Grid',
	itemId		: 'Lists_info',
	alias       : 'widget.Lists_info',
	store 		: 's_s_info',
	columnLines : true,
	
	margin		: 0,
	
	initComponent: function(){
		// The data store containing the list of states
		var states = Ext.create('Ext.data.Store', {
			fields: ['abbr', 'name'],
			data : [
				{"abbr":"AL", "name":"Alabama"},
				{"abbr":"AK", "name":"Alaska"},
				{"abbr":"AZ", "name":"Arizona"}
				//...
			]
		});
		
		// Create the combo box, attached to the states data store
		var cbtest = Ext.create('Ext.form.ComboBox', {
			fieldLabel: 'Choose State',
			store: states,
			queryMode: 'local',
			displayField: 'name',
			valueField: 'abbr',
			renderTo: Ext.getBody()
		});
		this.columns = [
			{ header: 'INFO_ID', dataIndex: 'INFO_ID'},
			{ header: 'INFO_NAMA', dataIndex: 'INFO_NAMA'},
			{ header: 'INFO_CABANG', dataIndex: 'INFO_CABANG'},
			{ header: 'INFO_ALAMAT', dataIndex: 'INFO_ALAMAT'},
			{ header: 'INFO_NOTELP', dataIndex: 'INFO_NOTELP'},
			{ header: 'INFO_NOFAX', dataIndex: 'INFO_NOFAX', field: cbtest},
			{ header: 'INFO_EMAIL', dataIndex: 'INFO_EMAIL'},
			{ header: 'INFO_WEBSITE', dataIndex: 'INFO_WEBSITE'},
			{ header: 'INFO_SLOGAN', dataIndex: 'INFO_SLOGAN'},
			{ header: 'INFO_LOGO', dataIndex: 'INFO_LOGO'},
			{ header: 'INFO_ICON', dataIndex: 'INFO_ICON'},
			{ header: 'INFO_BACKGROUND', dataIndex: 'INFO_BACKGROUND'},
			{ header: 'INFO_THEME', dataIndex: 'INFO_THEME'}];
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [{
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create'
				}, {
					text	: 'Delete',
					itemId	: 'delete',
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
				store: 's_s_info',
				dock: 'bottom',
				displayInfo: false
			}
		];
		this.callParent(arguments);
	}

});