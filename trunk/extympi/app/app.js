
//@require @packageOverrides

Ext.Loader.setPath('Ext.ux', './assets/ext-4.2/src/ux');

Ext.application({

    name: 'YMPI',
    
    appFolder: 'extympi/app',

    requires: [
        'Ext.state.CookieProvider',
        'Ext.window.MessageBox',
        'Ext.tip.QuickTipManager',
        'YMPI.store.Examples',
        'Ext.ModelManager',
        'Ext.form.*',
        'Ext.grid.plugin.RowEditing',
        'Ext.grid.plugin.Editing',
        'Ext.grid.RowEditor',
        'Ext.window.MessageBox',
        'Ext.layout.component.field.*',
        'Ext.ux.CheckColumn',
        'YMPI.view.Viewport',
        'YMPI.view.Header',
        'YMPI.view.Navigation',
        'YMPI.view.ContentPanel',
        'YMPI.view.MASTER.GRADE',
        'YMPI.view.MASTER.JabatanList',
        'YMPI.view.MASTER.UnitKerjaList',
        'YMPI.view.MASTER.UNITKERJA',
        'YMPI.view.AKSES.PermissionGroup',
        'YMPI.view.AKSES.User',
        'YMPI.view.AKSES.UserGroup',
        'YMPI.view.AKSES.USERMANAGE'
    ],

    controllers: [
        'Main', 'GRADE', 'UNITKERJA', 'USERMANAGE'
    ],

    autoCreateViewport: true,

    init: function() {
        Ext.setGlyphFontFamily('Pictos');
        Ext.tip.QuickTipManager.init();
        Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));
    }
});
