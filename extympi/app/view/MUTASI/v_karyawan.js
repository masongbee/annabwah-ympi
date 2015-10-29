Ext.define('YMPI.view.MUTASI.v_karyawan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_karyawan'],
	
	title		: 'karyawan',
	itemId		: 'Listkaryawan',
	alias       : 'widget.Listkaryawan',
	store 		: 's_karyawan',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	plugins: [{
        ptype: 'rowexpander',
        rowBodyTpl : new Ext.XTemplate(
			'<table>',
				'<tr>',
					'<td><div style="float: left; width: 400px;">',
						'<p><b>NIK:</b> {NIK}</p>',
						'<p><b>NAMA:</b> {NAMAKAR}</p><br>',
						'<p><b>ALAMAT:</b> {ALAMAT}, {DESA} RT/RW: {RT}/{RW}, {KECAMATAN}, {KOTA}</p>',
					'</div>',
					'<div style="float: left;">',
						'<img src="./photos/{FOTO}" height="60px" />',
					'</div>',
					'</td>',
				'</tr>',
			'</table>'
			)
    }, 'bufferedrenderer'],
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'NIK',
				dataIndex: 'NIK',
				locked   : true
			},{
				header: 'NAMAKAR',
				dataIndex: 'NAMAKAR',
				locked   : true
			}/*,{
				header: 'NAMASINGKAT',
				dataIndex: 'NAMASINGKAT'
			},{
				header: 'IDJAB',
				dataIndex: 'IDJAB'
			}*/,{
				header: 'JABATAN',
				dataIndex: 'NAMAJAB',
				width: 200,
				renderer: function(value, metaData, record){
					return record.data.IDJAB+' - '+record.data.NAMAJAB;
				}
			}/*,{
				header: 'KODEUNIT',
				dataIndex: 'KODEUNIT'
			}*/,{
				header: 'UNIT',
				dataIndex: 'NAMAUNIT',
				width: 200,
				renderer: function(value, metaData, record){
					return record.data.KODEUNIT+' - '+record.data.NAMAUNIT;
				}
			}/*,{
				header: 'KODEKEL',
				dataIndex: 'KODEKEL'
			}*/,{
				header: 'KELOMPOK',
				dataIndex: 'NAMAKEL',
				width: 120
			}/*,{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB'
			}*/,{
				header: 'LEVEL JABATAN',
				dataIndex: 'NAMALEVEL',
				width: 120
			},{
				header: 'GRADE',
				dataIndex: 'GRADE'
			},{
				header: 'KODESP',
				dataIndex: 'KODESP'
			},{
				header: 'NPWP',
				dataIndex: 'NPWP'
			},{
				header: 'TGLMASUK',
				dataIndex: 'TGLMASUK',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{ header: 'JENISKEL', dataIndex: 'JENISKEL'},{ header: 'ALAMAT', dataIndex: 'ALAMAT'},{ header: 'DESA', dataIndex: 'DESA'},{ header: 'RT', dataIndex: 'RT'},{ header: 'RW', dataIndex: 'RW'},{ header: 'KECAMATAN', dataIndex: 'KECAMATAN'},{ header: 'KOTA', dataIndex: 'KOTA'},{ header: 'TELEPON', dataIndex: 'TELEPON'},{ header: 'TMPLAHIR', dataIndex: 'TMPLAHIR'},{ header: 'TGLLAHIR', dataIndex: 'TGLLAHIR', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'ANAKKE', dataIndex: 'ANAKKE'},{ header: 'JMLSAUDARA', dataIndex: 'JMLSAUDARA'},{ header: 'PENDIDIKAN', dataIndex: 'PENDIDIKAN'},{ header: 'JURUSAN', dataIndex: 'JURUSAN'},{ header: 'NAMASEKOLAH', dataIndex: 'NAMASEKOLAH'},{ header: 'AGAMA', dataIndex: 'AGAMA'},{ header: 'NAMAAYAH', dataIndex: 'NAMAAYAH'},{ header: 'STATUSAYAH', dataIndex: 'STATUSAYAH'},{ header: 'ALAMATAYAH', dataIndex: 'ALAMATAYAH'},{ header: 'PENDDKAYAH', dataIndex: 'PENDDKAYAH'},{ header: 'PEKERJAYAH', dataIndex: 'PEKERJAYAH'},{ header: 'NAMAIBU', dataIndex: 'NAMAIBU'},{ header: 'STATUSIBU', dataIndex: 'STATUSIBU'},{ header: 'ALAMATIBU', dataIndex: 'ALAMATIBU'},{ header: 'PENDDKIBU', dataIndex: 'PENDDKIBU'},{ header: 'PEKERJIBU', dataIndex: 'PEKERJIBU'},{ header: 'KAWIN', dataIndex: 'KAWIN'},{ header: 'TGLKAWIN', dataIndex: 'TGLKAWIN', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'NAMAPASANGAN', dataIndex: 'NAMAPASANGAN'},{ header: 'ALAMATPAS', dataIndex: 'ALAMATPAS'},{ header: 'TMPLAHIRPAS', dataIndex: 'TMPLAHIRPAS'},{ header: 'TGLLAHIRPAS', dataIndex: 'TGLLAHIRPAS', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'AGAMAPAS', dataIndex: 'AGAMAPAS'},{ header: 'PEKERJPAS', dataIndex: 'PEKERJPAS'},{ header: 'KATPEKERJAAN', dataIndex: 'KATPEKERJAAN'},{ header: 'BHSJEPANG', dataIndex: 'BHSJEPANG'}
			,{
				xtype: 'checkcolumn',
				header: 'JAMSOSTEK',
				dataIndex: 'JAMSOSTEK'
			},{ header: 'TGLJAMSOSTEK', dataIndex: 'TGLJAMSOSTEK', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'STATUS', dataIndex: 'STATUS'},{ header: 'TGLSTATUS', dataIndex: 'TGLSTATUS', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'TGLMUTASI', dataIndex: 'TGLMUTASI', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'NOURUTKTRK', dataIndex: 'NOURUTKTRK'},{ header: 'TGLKONTRAK', dataIndex: 'TGLKONTRAK', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'LAMAKONTRAK', dataIndex: 'LAMAKONTRAK'},{ header: 'NOACCKAR', dataIndex: 'NOACCKAR'},{ header: 'NAMABANK', dataIndex: 'NAMABANK'},{ header: 'FOTO', dataIndex: 'FOTO'},{ header: 'USERNAME', dataIndex: 'USERNAME'},{ header: 'STATTUNKEL', dataIndex: 'STATTUNKEL'},{ header: 'ZONA', dataIndex: 'ZONA'}
			,{
				xtype: 'checkcolumn',
				header: 'STATTUNTRAN',
				dataIndex: 'STATTUNTRAN'
			}];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Export Excel',
						iconCls	: 'icon-excel',
						action	: 'xexcel'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Export PDF',
						iconCls	: 'icon-pdf',
						action	: 'xpdf'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}, '-', {
	                fieldLabel: 'Search',
	                labelWidth: 50,
	                xtype: 'searchfield',
	                store: 's_karyawan',
	                flex: 1
	            }]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_karyawan',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		//this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		//this.getView().saveState();
		//console.log(this.getView());
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});