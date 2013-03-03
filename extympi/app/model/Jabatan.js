Ext.define('YMPI.model.Jabatan', {
    extend: 'Ext.data.Model',
    alias		: 'widget.jabatanModel',
    fields		: ['ID', 'KODEJAB', 'NAMAJAB', 'KODEUNIT'],
	idProperty	: 'KODEJAB'
});
