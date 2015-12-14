Ext.define('YMPI.view.LAPORAN.v_lapseleksikar_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_lapseleksikar_form',
	
	// title		: 'Filter',
    bodyPadding	: 5,
    autoScroll	: true,
	//comboFilter	: [],
    
    initComponent: function(){
		var me = this;

		/* STORE start */
		
		/* STORE end */

		/*
		 * Deklarasi variable setiap field
		 */
		var LOWONGAN_field = Ext.create('Ext.form.ComboBox', {
			name: 'GELLOW',
			fieldLabel: 'Gelombang Lowongan',
			store: 's_lowongan',
			queryMode: 'local',
			displayField: 'GELLOW',
			valueField: 'GELLOW',
			emptyText: 'Daftar Gelombang Lowongan',
			allowBlank: false,
			labelWidth: 180,
			listeners: {
				select: function(field, records, e){
					POSISILOWONGAN_field.getStore().getProxy().extraParams.gellow = records[0].data.GELLOW;
					POSISILOWONGAN_field.getStore().load();
				}
			}
		});
		var POSISILOWONGAN_field = Ext.create('Ext.form.ComboBox', {
			name: 'IDJAB',
			fieldLabel: 'Posisi Lowongan',
			store: 's_lapposisilowongan',
			queryMode: 'local',
			displayField: 'NAMAUNIT',
			valueField: 'IDJAB',
			emptyText: 'Daftar Posisi Lowongan',
			allowBlank: false,
			labelWidth: 180,
			listeners: {
				select: function(field, records, e){
					KODEJAB_field.getStore().getProxy().extraParams.gellow = LOWONGAN_field.getValue();
					KODEJAB_field.getStore().getProxy().extraParams.idjab  = records[0].data.IDJAB;
					KODEJAB_field.getStore().load();
				}
			}
		});
		var KODEJAB_field = Ext.create('Ext.form.ComboBox', {
			name: 'KODEJAB',
			fieldLabel: 'Level Jabatan',
			store: 's_laplevellowongan',
			queryMode: 'local',
			displayField: 'NAMALEVEL',
			valueField: 'KODEJAB',
			emptyText: 'Daftar Level Jabatan',
			allowBlank: false,
			labelWidth: 180
		});
		var JENISSELEKSI_field = Ext.create('Ext.form.ComboBox', {
			name: 'KODESELEKSI',
			fieldLabel: 'Tahapan Seleksi',
			store: 's_jnsseleksi',
			queryMode: 'local',
			displayField: 'NAMASELEKSI',
			valueField: 'KODESELEKSI',
			emptyText: 'Daftar Tahapan Seleksi',
			allowBlank: false,
			labelWidth: 180
		});
		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
			listeners: {
				afterrender: function(){
					//me.down(detail_tabs).setDisabled(true);
				}
			},
            items: [{
				xtype: 'form',
				bodyStyle: 'border-width: 0px;',
				layout: 'column',
				items: [{
					//left column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						LOWONGAN_field,POSISILOWONGAN_field
					]
				} ,{
					xtype: 'splitter',
					columnWidth:0.02
				} ,{
					//right column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						KODEJAB_field,JENISSELEKSI_field
					]
				}]
			}],
			
	        buttons: [{
                iconCls: 'icon-reset',
                text: 'Search',
                action: 'searchall'
            }]
        });
        
        this.callParent();
    }
});