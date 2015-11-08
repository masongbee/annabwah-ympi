Ext.define('YMPI.controller.PELAMAR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_pelamar'],
	models: ['m_pelamar'],
	stores: ['s_pelamar'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpelamar',
		selector: 'Listpelamar'
	}],


	init: function(){
		this.control({
			'Listpelamar': {
				'afterrender': this.pelamarAfterRender,
				'selectionchange': this.enableDelete,
				'beforeselect': this.beforeselectGrid,
				'beforeedit': this.beforeeditGrid
			},
			'Listpelamar button[action=create]': {
				click: this.createRecord
			},
			'Listpelamar button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpelamar button[action=mutasi]': {
				click: this.mutasiRecord
			}
		});
	},
	
	pelamarAfterRender: function(){
		var pelamarStore = this.getListpelamar().getStore();
		pelamarStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_pelamar');
		var r = Ext.ModelManager.create({
			KTP				: '',
			NAMAPELAMAR		: '',
			AGAMA			: '',
			ALAMAT			: '',
			JENISKEL		: '',
			JURUSAN			: '',
			KAWIN			: '',
			KOTA			: '',
			NAMASEKOLAH		: '',
			PENDIDIKAN		: '',
			TELEPON			: '',
			TGLLAHIR		: '',
			TMPLAHIR		: '',
			STATUSPELAMAR	: 'A',
			GELLOW			: '',
			KODEJAB			: '',
			IDJAB			: ''
		}, model);
		this.getListpelamar().getStore().insert(0, r);
		this.getListpelamar().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		if (selections.length > 0) {
			var data = selections[0].data;
			if (data.STATUSPELAMAR == 'F') {
				this.getListpelamar().down('#btndelete').setDisabled(true);
				this.getListpelamar().down('#btnmutasi').setDisabled(false);
			} else {
				this.getListpelamar().down('#btnmutasi').setDisabled(true);

				if (data.STATUSPELAMAR == 'A') {
					this.getListpelamar().down('#btndelete').setDisabled(false);
				} else{
					this.getListpelamar().down('#btndelete').setDisabled(true);
				};
			};
		} else {
			this.getListpelamar().down('#btndelete').setDisabled(!selections.length);
			this.getListpelamar().down('#btnmutasi').setDisabled(!selections.length);
		}
		
	},

	beforeselectGrid: function(thisme, record, index){
		if (record.data.STATUSPELAMAR == 'F') {
			return true;
		} else {
			return false;
		};
	},

	beforeeditGrid: function(editor, e){
		var statuspelamar = e.record.get('STATUSPELAMAR');
		
		if (statuspelamar == 'A') {
			return true;
		}
		return false;
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpelamar().getStore();
		var selection = this.getListpelamar().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KTP = "'+selection.data.KTP+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},

	mutasiRecord: function(dataview, selections) {
		var mainthis = this;
		var getstore = this.getListpelamar().getStore();
		var selections = this.getListpelamar().getSelectionModel().getSelection();
		var jsonData = Ext.encode(Ext.pluck(selections, 'data'));

		var STATUS_field = Ext.create('Ext.form.field.ComboBox', {
			fieldLabel: 'Status <font color=red>(*)</font>',
			name: 'STATUS', /* column name of table */
			store: Ext.create('Ext.data.Store', {
	    	    fields: ['value', 'display'],
	    	    data : [
	    	        {"value":"K", "display":"KONTRAK"},
	    	        {"value":"C", "display":"PERCOBAAN"}
	    	    ]
	    	}),
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			width: 120
		});
		var TGLMASUK_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLMASUK', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl Masuk <font color=red>(*)</font>',
			labelWidth: 90,
			width: 210
		});
		var TGLKONTRAK_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLKONTRAK', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl Kontrak <font color=red>(*)</font>',
			labelWidth: 90,
			width: 210
		});
		var LAMAKONTRAK_field = Ext.create('Ext.form.field.Number', {
			name: 'LAMAKONTRAK', /* column name of table */
			fieldLabel: 'Lama Kontrak',
			maxLength: 11, /* length of column name */
			labelWidth: 90,
			width: 147
		});

		var form = Ext.widget('form', {
	        width: 340,
	        border: false,
            bodyPadding: 10,

	        fieldDefaults: {
	            labelAlign: 'left',
	            labelWidth: 90,
	            anchor: '100%'
	        },

	        items: [STATUS_field, TGLMASUK_field, TGLKONTRAK_field, LAMAKONTRAK_field],
	        buttons: [{
                text: 'Cancel',
                handler: function() {
                    this.up('form').getForm().reset();
                    this.up('window').hide();
                }
            },{
                text: 'Save',
                handler: function() {
                	var statusfield = STATUS_field.getValue();
                	var tglmasukfield = TGLMASUK_field.getValue();
                	var tglkontrakfield = TGLKONTRAK_field.getValue();
                	var lamakontrakfield = LAMAKONTRAK_field.getValue();
                	var getform = this.up('form').getForm();
                	var getwindow = this.up('window');

                    mainthis.mutasiRecordAction(jsonData,statusfield,tglmasukfield,tglkontrakfield,lamakontrakfield,getform,getwindow);
                }
            }]
	    });
		var win = Ext.widget('window', {
            title: 'Status Kekaryawanan',
            closeAction: 'hide',
            closable: false,
            width: 345,
            // height: 400,
            layout: 'fit',
            resizable: false,
            modal: true,
            items: form,
            defaultFocus: 'firstName'
        });
        win.show();
		/*Ext.Ajax.request({
			method: 'POST',
			url: 'c_pelamar/mutasiPelamar',
			params: {data: jsonData},
			success: function(response){
				getstore.load();
				var objResponse = Ext.JSON.decode(response.responseText);
				Ext.MessageBox.show({
					title: 'Info',
					msg: objResponse.message,
					buttons: Ext.MessageBox.OK,
					icon: Ext.Msg.INFO
				});
			}
		});*/
	},

	mutasiRecordAction: function(jsonData,statusfield,tglmasukfield,tglkontrakfield,lamakontrakfield,getform,getwindow){
		var getstore = this.getListpelamar().getStore();
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pelamar/mutasiPelamar',
			params: {
				data: jsonData,
				status: statusfield,
				tglmasuk: tglmasukfield,
				tglkontrak: tglkontrakfield,
				lamakontrak: lamakontrakfield
			},
			success: function(response){
				getform.reset();
				getwindow.hide();
				
				getstore.load();
				var objResponse = Ext.JSON.decode(response.responseText);
				Ext.MessageBox.show({
					title: 'Info',
					msg: objResponse.message,
					buttons: Ext.MessageBox.OK,
					icon: Ext.Msg.INFO
				});
			}
		});
	}
	
});