Ext.define('YMPI.model.m_s_info', {
	extend: 'Ext.data.Model',
	alias		: 'widget.s_infoModel',
	fields		: ['INFO_ID','INFO_NAMA','INFO_CABANG','INFO_ALAMAT','INFO_NOTELP','INFO_NOFAX','INFO_EMAIL','INFO_WEBSITE','INFO_SLOGAN','INFO_LOGO','INFO_ICON','INFO_BACKGROUND','INFO_THEME'],
	idProperty	: 'INFO_ID'
});