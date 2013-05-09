Ext.define('YMPI.view.AKSES.v_s_info_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_s_info_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update Info Setting',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		/* Tipe Int */
		var info_id_field = Ext.create('Ext.form.field.Number', {
			itemId: 'info_id_field',
			name: 'INFO_ID', /* column name of table */
			fieldLabel: 'Info Id',
			allowBlank: false /* jika primary_key */
		});
		/* Tipe Varchar */
		var info_nama_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_NAMA', /* column name of table */
			fieldLabel: 'Info Nama',
			maxLength: 150 /* length of column name */
		});
		var info_cabang_field = Ext.create('Ext.form.field.Number', {
			name: 'INFO_CABANG', /* column name of table */
			fieldLabel: 'Info Cabang'
		});
		var info_alamat_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_ALAMAT', /* column name of table */
			fieldLabel: 'Info Alamat',
			maxLength: 250 /* length of column name */
		});
		var info_notelp_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_NOTELP', /* column name of table */
			fieldLabel: 'Info Notelp',
			maxLength: 50 /* length of column name */
		});
		var info_nofax_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_NOFAX', /* column name of table */
			fieldLabel: 'Info Nofax',
			maxLength: 50 /* length of column name */
		});
		var info_email_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_EMAIL', /* column name of table */
			fieldLabel: 'Info Email',
			maxLength: 50 /* length of column name */
		});
		var info_website_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_WEBSITE', /* column name of table */
			fieldLabel: 'Info Website',
			maxLength: 100 /* length of column name */
		});
		var info_slogan_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_SLOGAN', /* column name of table */
			fieldLabel: 'Info Slogan',
			maxLength: 150 /* length of column name */
		});
		var info_logo_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_LOGO', /* column name of table */
			fieldLabel: 'Info Logo',
			maxLength: 150 /* length of column name */
		});
		var info_icon_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_ICON', /* column name of table */
			fieldLabel: 'Info Icon',
			maxLength: 150 /* length of column name */
		});
		var info_background_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_BACKGROUND', /* column name of table */
			fieldLabel: 'Info Background',
			maxLength: 150 /* length of column name */
		});
		var info_theme_field = Ext.create('Ext.form.field.Text', {
			name: 'INFO_THEME', /* column name of table */
			fieldLabel: 'Info Theme',
			maxLength: 150 /* length of column name */
		});
		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [info_id_field,
					info_nama_field,
					info_cabang_field,
					info_alamat_field,
					info_notelp_field,
					info_nofax_field,
					info_email_field,
					info_website_field,
					info_slogan_field,
					info_logo_field,
					info_icon_field,
					info_background_field,
					info_theme_field],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                disabled: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
				itemId: 'create',
                text: 'Create',
                action: 'create'
            }, {
                iconCls: 'icon-reset',
                text: 'Cancel',
                action: 'cancel'
            }]
        });
        
        this.callParent();
    }
});