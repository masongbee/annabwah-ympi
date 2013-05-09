
//@require @packageOverrides

Ext.Loader.setPath('Ext.ux', './assets/ext-4.2/src/ux');
Ext.Loader.setPath('Ext.util', './assets/ext-4.2/src/util');

Ext.application({

    name: 'YMPI',
    
    appFolder: 'extympi/app',

    requires: [
        'Ext.state.CookieProvider',
        'Ext.window.MessageBox',
        'Ext.tip.QuickTipManager',
        'Ext.ModelManager',
        'Ext.form.*',
        'Ext.grid.plugin.RowEditing',
        'Ext.grid.plugin.Editing',
        'Ext.grid.RowEditor',
        'Ext.window.MessageBox',
        'Ext.layout.component.field.*',
        //'Ext.ux.CheckColumn',
        'Ext.ux.RowExpander',
        'Ext.XTemplate',
        'YMPI.store.Examples',
        'YMPI.view.Viewport',
        'YMPI.view.Header',
        'YMPI.view.Navigation',
        'YMPI.view.ContentPanel'
    ],

    controllers: [
        'Main', 'UNITKERJA', 'USERMANAGE', 'KARYAWAN', 'IMPORTPRES', 'HITPRES', 'POSTPRES',
        'MOHONIZIN', 'MOHONCUTI', 'KOMPENCUTI', 'SPLEMBUR', 'GRADE','CUTITAHUNAN', 'UPAHPOKOK', 'S_INFO'
    ],

    autoCreateViewport: true,

    init: function() {
        Ext.setGlyphFontFamily('Pictos');
        Ext.tip.QuickTipManager.init();
        Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));
        
        if (Ext.util.Format) {
			Ext.apply(Ext.util.Format, {
				thousandSeparator : ".",
				decimalSeparator  : ","
			});
		}
    }
});
