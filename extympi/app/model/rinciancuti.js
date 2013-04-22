Ext.define('YMPI.model.rinciancuti', {
    extend: 'Ext.data.Model',
    alias		: 'widget.permohonancutiModel',
    fields		: ['NOCUTI', 'NOURUT', 'NIK', 'JENISABSEN', 'LAMA', 'TGLMULAI', 'TGLSAMPAI', 'SISACUTI'],
	idProperty	: 'NOCUTI'
});