Ext.define('YMPI.view.MUTASI.KaryawanForm', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.KaryawanForm',
	
	//title		: 'Create/Update Karyawan',
    frame		: true,
    bodyPadding	: 5,
    autoScroll	: true,
    collapsed	: false,
    
    initComponent: function(){
    	var agamas = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"I", "display":"Islam"},
    	        {"value":"P", "display":"Kristen Protestan"},
    	        {"value":"K", "display":"Kristen Katholik"},
    	        {"value":"H", "display":"Hindu"},
    	        {"value":"B", "display":"Budha"},
    	        {"value":"C", "display":"Konghucu"}
    	    ]
    	});
    	
        Ext.apply(this, {
            //width: 550,
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip'
            },

            items: [{
                xtype: 'fieldset',
                title: 'Informasi Identitas',
                defaultType: 'textfield',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },
                items: [{
                    labelWidth: 120,
                    fieldLabel: 'Nama',
                    name: 'NAMAKAR',
                    allowBlank: false
                }, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Laki-laki/Perempuan',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: 'true'
                	},
                	items: [{
                        xtype: 'radiogroup',
                        flex: 2,
                        layout: {
                            autoFlex: false
                        },
                        defaults: {
                        	name: 'JENISKEL',
                            margin: '0 15 0 0'
                        },
                        items: [{
                            inputValue: 'L',
                            boxLabel: 'Laki-laki',
                            checked: true
                        }, {
                            inputValue: 'P',
                            boxLabel: 'Perempuan'
                        }]
                    }]
                }, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Tempat, Tgl Lahir',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: 'true'
                	},
                	items: [{
                		name: 'TMPLAHIR',
                		fieldLabel: 'Tempat Lahir',
                		emptyText: 'Tempat',
                		allowBlank: false
                	}, {
                		xtype: 'label',
                		text: ',',
                		margin: '10 5 0 5'
                	}, {
                		name: 'TGLLAHIR',
                        fieldLabel: 'Tanggal',
                        width: 40,
                        emptyText: 'Tgl',
                        margin: '0 5 0 0',
                        allowBlank: false
                	}, /*{
                        xtype: 'combobox',
                        name: 'BLNLAHIR',
                        fieldLabel: 'Bulan Lahir',
                        displayField: 'name',
                        valueField: 'num',
                        queryMode: 'local',
                        emptyText: 'Bln',
                        margins: '0 5 0 0',
                        store: new Ext.data.Store({
                            fields: ['name', 'num'],
                            data: (function() {
                                var data = [];
                                    Ext.Array.forEach(Ext.Date.monthNames, function(name, i) {
                                    data[i] = {name: name, num: i + 1};
                                });
                                return data;
                            })()
                        }),
                        width: 100,
                        allowBlank: false,
                        forceSelection: true
                    }, {
                    	name: 'THNLAHIR',
                    	fieldLabel: 'Tahun Lahir',
                    	emptyText: 'Tahun',
                    	width: 50,
                    	allowBlank: false
                    }*/]
                }, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Agama',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: 'true'
                	},
                	items: [{
                		xtype: 'combobox',
                    	name: 'AGAMA',
                    	fieldLabel: 'Agama',
                        store: agamas,
                        queryMode: 'local',
                        displayField: 'display',
                        valueField: 'value',
                        width: 176
                	}]
                }, {
                    fieldLabel: 'Alamat',
                    name: 'ALAMAT',
                    flex: 1,
                    allowBlank: false
                }, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Desa',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: 'true'
                	},
                	items: [{
                		name: 'DESA',
                		fieldLabel: 'Desa',
                		emptyText: 'Desa',
                		allowBlank: false
                	}, {
                		xtype: 'label',
                		text: 'RT/RW',
                		margin: '5 5 0 5'
                	}, {
                		name: 'RT',
                		fieldLabel: 'RT',
                		emptyText: 'RT',
                		width: 50,
                		allowBlank: false
                	}, {
                		xtype: 'label',
                		text: '/',
                		margin: '5 5 0 5'
                	}, {
                		name: 'RW',
                		fieldLabel: 'RW',
                		emptyText: 'RW',
                		width: 50,
                		allowBlank: false
                	}, {
                		xtype: 'label',
                		text: 'Telepon',
                		margin: '5 5 0 5'
                	}, {
                        name: 'TELEPON',
                        flex: 2,
                        emptyText: 'xxx-xxx-xxxx',
                        maskRe: /[\d\-]/,
                        regex: /^\d{3}-\d{3}-\d{4}$/,
                        regexText: 'Must be in the format xxx-xxx-xxxx'
                    }]
                }]
            }],

	        buttons: [/*{
	            text: 'Reset',
	            scope: this,
	            handler: this.onResetClick
	        }, {
	            text: 'Complete Purchase',
	            width: 150,
	            scope: this,
	            handler: this.onCompleteClick
	        },*/'->', {
                iconCls: 'icon-save',
                itemId: 'saveForm',
                text: 'Save',
                disabled: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
                text: 'Create',
                action: 'create'
            }, {
                iconCls: 'icon-reset',
                text: 'Cancel',
                //scope: this,
                //handler: this.onResetClick
                action: 'cancel'
            }]
        });
        
        this.callParent();
    },
    
    onResetClick: function(){
        this.getForm().reset();
    },
    
    onCompleteClick: function(){
        var form = this.getForm();
        if (form.isValid()) {
            Ext.MessageBox.alert('Submitted Values', form.getValues(true));
        }
    },
    
    onMailingAddrFieldChange: function(field){
        var copyToBilling = this.down('[name=billingSameAsMailing]').getValue(),
            copyField = this.down('[name=' + field.billingFieldName + ']');

        if (copyToBilling) {
            copyField.setValue(field.getValue());
        } else {
            copyField.clearInvalid();
        }
    },
    
    /**
     * Enables or disables the billing address fields according to whether the checkbox is checked.
     * In addition to disabling the fields, they are animated to a low opacity so they don't take
     * up visual attention.
     */
    onSameAddressChange: function(box, checked){
        var fieldset = box.ownerCt;
        Ext.Array.forEach(fieldset.previousSibling().query('textfield'), this.onMailingAddrFieldChange, this);
        Ext.Array.forEach(fieldset.query('textfield'), function(field) {
            field.setDisabled(checked);
            // Animate the opacity on each field. Would be more efficient to wrap them in a container
            // and animate the opacity on just the single container element, but IE has a bug where
            // the alpha filter does not get applied on position:relative children.
            // This must only be applied when it is not IE6, as it has issues with opacity when cleartype
            // is enabled
            if (!Ext.isIE6) {
                field.el.animate({opacity: checked ? 0.3 : 1});
            }
        });
    }
});