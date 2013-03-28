Ext.define('YMPI.model.Jabatan', {
    extend: 'Ext.data.Model',
    alias		: 'widget.jabatanModel',
    fields		: ['KODEUNIT', 'KODEJAB', 'NAMAJAB'],
	idProperty	: 'KODEJAB'
});
