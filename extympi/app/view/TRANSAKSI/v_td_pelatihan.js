Ext.define('YMPI.view.TRANSAKSI.v_td_pelatihan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_td_pelatihan'],
	
	title		: 'td_pelatihan',
	itemId		: 'Listtd_pelatihan',
	alias       : 'widget.Listtd_pelatihan',
	store 		: 's_td_pelatihan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;

		var upload_form = Ext.create('Ext.form.Panel', {
			width: 300,
			frame: false,
			bodyPadding: 0,
			
			items: [{
				xtype: 'fieldcontainer',
				layout: 'hbox',
				items: [{
					xtype: 'filefield',
					emptyText: 'Select a file to upload',
					name: 'userfile',
					width: 220
				},{
					xtype: 'splitter'
				},{
					xtype: 'button',
					text: 'Upload',
					handler: function(){
						var form = this.up('form').getForm();
						if(form.isValid()){
							form.submit({
								url: 'c_td_pelatihan/do_upload',
								waitMsg: 'Uploading your file...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+obj.skeepdata+' data yang tidak tersimpan.');
									me.getStore().reload();
								},
								failure: function() {
									Ext.Msg.alert("Error", Ext.JSON.decode(this.response.responseText).msg);
								}
							});
						}
					}
				}]
			}]
		});
		
		this.columns = [
			{
				header: 'KARYAWAN',
				dataIndex: 'NIK',
				width: 200,
				renderer: function(value, metaData, record){
					return record.data.NIK+' - '+record.data.NAMAKAR;
				}
			},{
				header: 'KODETRAINING',
				dataIndex: 'KODETRAINING',
				width: 120
			},{
				header: 'NAMATRAINING',
				dataIndex: 'NAMATRAINING',
				width: 300
			},{
				header: 'TAHUN',
				dataIndex: 'TAHUN'
			},{
				header: 'TEMPAT',
				dataIndex: 'TEMPAT'
			},{
				header: 'TGLMULAI',
				dataIndex: 'TGLMULAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TGLSAMPAI',
				dataIndex: 'TGLSAMPAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'PENYELENGGARA',
				dataIndex: 'PENYELENGGARA',
				width: 140
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN'
			}];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [upload_form, '-', {
	                fieldLabel: 'Search',
	                labelWidth: 50,
	                xtype: 'searchfield',
	                store: 's_td_pelatihan',
	                flex: 1
	            }]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_td_pelatihan',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});