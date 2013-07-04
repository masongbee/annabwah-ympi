
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
		'Ext.ux.grid.GridHeaderFilters',
        //'Ext.ux.CheckColumn',
        'Ext.ux.RowExpander',
        'Ext.XTemplate',
        'Ext.ux.form.NumericField',
        'YMPI.store.Examples',
        'YMPI.view.Viewport',
        'YMPI.view.Header',
        'YMPI.view.Navigation',
        'YMPI.view.ContentPanel'
    ],

    controllers: [
        'Main', 'UNITKERJA', 'USERMANAGE', 'KARYAWAN', 'IMPORTPRES', 'HITPRES', 'POSTPRES',
        'MOHONIZIN', 'MOHONCUTI', 'RINCIANCUTI', 'KOMPENCUTI', 'SPLEMBUR', 'GRADE','CUTITAHUNAN', 'UPAHPOKOK', 'S_INFO', 'EDITPRES', 'IMPORTPRES', 'RENCANALEMBUR',
		'KELUARGA', 'HITUNGGAJI', 'TPEKERJAAN', 'SKILL', 'RIWAYATKERJA', 'RIWAYATKERJAYMPI', 'TKELUARGA', 'TBHS'
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
