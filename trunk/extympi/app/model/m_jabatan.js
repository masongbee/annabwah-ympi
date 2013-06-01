Ext.define('YMPI.model.m_jabatan', {
	extend: 'Ext.data.Model',
	alias		: 'widget.jabatanModel',
	fields		: [
		'KODEUNIT'
		,'KODEJAB'
		,'NAMAJAB'
		,{name: 'HITUNGLEMBUR', type: 'bool'}
		,{name: 'KOMPENCUTI', type: 'bool'}
		,'KODEAKUN'
	]
});