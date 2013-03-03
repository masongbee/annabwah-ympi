Ext.define('YMPI.model.UnitKerja', {
    extend: 'Ext.data.Model',
    alias		: 'widget.unitKerjaModel',
    fields		: ['ID', 'P_KODEUNIT', 'KODEUNIT', 'NAMAUNIT'],
	idProperty	: 'KODEUNIT'
});
