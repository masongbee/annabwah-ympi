Ext.define('YMPI.view.List', {
    extend: 'Ext.tree.Panel',
    xtype: 'exampleList',
    
    requires: [
        'YMPI.store.Examples',
        'Ext.ModelManager',
        'Ext.form.*',
        'Ext.grid.plugin.RowEditing',
        'Ext.grid.plugin.Editing',
        'Ext.grid.RowEditor',
        'Ext.window.MessageBox',
        'Ext.layout.component.field.*',
        'Ext.ux.CheckColumn',
        'YMPI.view.dataMaster.Grade',
        'YMPI.view.dataMaster.Jabatan',
        'YMPI.view.dataMaster.UnitKerja',
        'YMPI.view.dataMaster.UnitKerjaDanJabatan',
        'YMPI.view.file.PermissionGroup',
        'YMPI.view.file.User',
        'YMPI.view.file.UserGroup',
        'YMPI.view.file.UserManager'
    ],
    
    title: 'Daftar Menu',
    rootVisible: false,
	
	cls: 'examples-list',
    
    lines: false,
    useArrows: true,
    
    store: 'Examples'
});
