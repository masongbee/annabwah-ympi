Ext.define('YMPI.model.lembur', {
    extend: 'Ext.data.Model',
    alias		: 'widget.lemburModel',
    fields		: ['NOLEMBUR', 'KODEUNIT', 'TANGGAL', 'KEPERLUAN', 'NIKUSUL', 'NIKSETUJU', 'NIKDIKETAHUI', 'NIKPERSONALIA', 'TGLSETUJU', 'TGLPERSONALIA', 'USERNAME'],
	idProperty	: 'NOLEMBUR'
});
