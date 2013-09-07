Ext.define('YMPI.model.m_jabatan', {
	extend: 'Ext.data.Model',
	alias		: 'widget.jabatanModel',
	fields		: [
		'IDJAB'
		,'KODEUNIT'
		,'NAMAJAB'
		,{name: 'HITUNGLEMBUR', type: 'bool'}
		,{name: 'KOMPENCUTI', type: 'bool'}
		,'KODEAKUN'
	]
});