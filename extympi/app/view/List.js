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
        'YMPI.view.MASTER.GRADE',
        'YMPI.view.MASTER.JabatanList',
        'YMPI.view.MASTER.UnitKerjaList',
        'YMPI.view.MASTER.UNITKERJA',
        'YMPI.view.AKSES.PermissionGroup',
        'YMPI.view.AKSES.User',
        'YMPI.view.AKSES.UserGroup',
        'YMPI.view.AKSES.USERMANAGE',
        'YMPI.view.PROSES.IMPORTPRES',
        'YMPI.view.PROSES.ImportPresensi',
        'YMPI.view.PROSES.Presensi'
    ],
    
    title: 'Daftar Menu',
    rootVisible: false,
	
	cls: 'examples-list',
    
    lines: false,
    useArrows: true,
    
    store: 'Examples'
});
