Ext.define('YMPI.view.LAPORAN.v_lapkrjkar', {
	extend: 'Ext.grid.Panel',
	
	title		: 'Hasil Pencarian',
	itemId		: 'v_lapkrjkar',
	alias       : 'widget.v_lapkrjkar',
	store 		: 's_lapkrjkar',
	columnLines : true,
	frame		: false,
	autoScroll	: true,
	margin		: 0,
	selectedIndex : -1,

	plugins: 'bufferedrenderer',
	
	initComponent: function(){
		this.columns = [];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
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
					}]
				}]
			})
		];
		this.callParent(arguments);
	}

});