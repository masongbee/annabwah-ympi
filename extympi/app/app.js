
//@require @packageOverrides

Ext.Loader.setPath('Ext.ux', './assets/ext-4.2/src/ux');
Ext.Loader.setPath('Ext.util', './assets/ext-4.2/src/util');

Ext.application({

    name: 'YMPI',
    
    appFolder: 'extympi/app',

    requires: [
		'Ext.ProgressBar',
        'Ext.state.CookieProvider',
        'Ext.window.MessageBox',
        'Ext.tip.QuickTipManager',
        'Ext.ModelManager',
        'Ext.form.*',
        //'Ext.grid.plugin.RowEditing',
        //'Ext.grid.plugin.Editing',
        //'Ext.grid.RowEditor',
        'Ext.window.MessageBox',
        'Ext.layout.component.field.*',
		'Ext.ux.grid.GridHeaderFilters',
		'Ext.grid.*',
		'Ext.data.*',
		'Ext.util.*',
		'Ext.grid.plugin.BufferedRenderer',
        //'Ext.ux.CheckColumn',
        'Ext.ux.RowExpander',
        'Ext.XTemplate',
        'Ext.ux.form.NumericField',
		'Ext.ux.grid.FiltersFeature',
		'Ext.toolbar.Paging',
		'Ext.override.ComboBox',
        'YMPI.store.Examples',
        'YMPI.view.Viewport',
        'YMPI.view.Header',
        'YMPI.view.Navigation',
        'YMPI.view.ContentPanel',
		'Ext.ux.egen.Printer',
        'Ext.ux.form.SearchField'
    ],

    controllers: [
        'Main', 'GPASS', 'UNITKERJA','PERIODEGAJI', 'USERMANAGE', 'KARYAWAN', 'IMPORTPRES', 'HITPRES', 'POSTPRES', 'JENISABSEN','KALENDERLIBUR','PRESENSILEMBUR','SHIFTJAMKERJA','SHIFT','DETILSHIFT','JEMPUTANKAR',
        'PERMOHONANIJIN', 'PERMOHONANCUTI', 'KOMPENCUTI', 'SPLEMBUR', 'GRADE','CUTITAHUNAN', 'UPAHPOKOK', 'S_INFO', 'EDITPRES', 'RENCANALEMBUR',
		'KELUARGA', 'HITUNGGAJI', 'TPEKERJAAN', 'SKILL', 'RIWAYATKERJA', 'RIWAYATKERJAYMPI', 'TKELUARGA', 'TBHS',
		'RIWAYATTRAINING', 'RIWAYATSEHAT', 'PENGHARGAAN', 'TJABATAN', 'TTRANSPORT', 'INSDISIPLIN', 'LEMBUR', 'TSHIFT', 'TAMBAHANLAIN2',
		'POTONGANLAIN2', 'PCICILAN', 'UANGSIMPATI', 'BONUS', 'TKEHADIRAN', 'THR', 'PERIODEGAJI', 'TRMAKAN', 'TKACAMATA',
		'KELOMPOK', 'LEVELJABATAN', 'TQCP', 'TMAKAN', 'POTONGANSP', 'JENISTAMBAHAN', 'JENISPOTONGAN',
		'MONKAR', 'LAPNAMETAG', 'NAMETAG', 'RINCIANCUTI', 'PJAMSOSTEK',
		'RPRESENSI','PRESENSIKHUSUS','LAPGAJI','TD_KELOMPOK','TD_TRAINING','TD_TRAINER','TD_PELATIHAN','TD_EVEFEKTIVITAS',
        'REKAPJEMPUTAN','LOWONGAN','POSISILOWONGAN','PELAMAR','TAHAPSELEKSI','GROUPMANAGE','LAPTRAINING','LAPKARLEMBUR'

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
