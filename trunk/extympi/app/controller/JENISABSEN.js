Ext.define('YMPI.controller.JENISABSEN',{
			extend: 'Ext.app.Controller',
			views: ['MASTER.v_jenisabsen'],
			models: ['m_jenisabsen'],
			stores: ['s_jenisabsen'],
			
			requires: ['Ext.ModelManager'],
			
			refs: [{
				ref: 'Listjenisabsen',
				selector: 'Listjenisabsen'
			}],


			init: function(){
				this.control({
					'Listjenisabsen': {
						'afterrender': this.jenisabsenAfterRender,
						'selectionchange': this.enableDelete
					},
					'Listjenisabsen button[action=create]': {
						click: this.createRecord
					},
					'Listjenisabsen button[action=delete]': {
						click: this.deleteRecord
					},
					'Listjenisabsen button[action=xexcel]': {
						click: this.export2Excel
					},
					'Listjenisabsen button[action=xpdf]': {
						click: this.export2PDF
					},
					'Listjenisabsen button[action=print]': {
						click: this.printRecords
					}
				});
			},
			
			jenisabsenAfterRender: function(){
				var jenisabsenStore = this.getListjenisabsen().getStore();
				jenisabsenStore.load();
			},
			
			createRecord: function(){
				var model		= Ext.ModelMgr.getModel('YMPI.model.m_jenisabsen');
				var r = Ext.ModelManager.create({
				JENISABSEN		: '00',KETERANGAN		: '00'}, model);
				this.getListjenisabsen().getStore().insert(0, r);
				this.getListjenisabsen().rowEditing.startEdit(0,0);
			},
			
			enableDelete: function(dataview, selections){
				this.getListjenisabsen().down('#btndelete').setDisabled(!selections.length);
			},
			
			deleteRecord: function(dataview, selections){
				var getstore = this.getListjenisabsen().getStore();
				var selection = this.getListjenisabsen().getSelectionModel().getSelection()[0];
				if(selection){
					Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: JENISABSEN = "'+selection.data.JENISABSEN+'"?', function(btn){
						if (btn == 'yes'){
							getstore.remove(selection);
							getstore.sync();
						}
					});
					
				}
			},
			
			export2Excel: function(){
				var getstore = this.getListjenisabsen().getStore();
				var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
				
				Ext.Ajax.request({
					method: 'POST',
					url: 'c_jenisabsen/export2Excel',
					params: {data: jsonData},
					success: function(response){
						window.location = ('./temp/'+response.responseText);
					}
				});
			},
			
			export2PDF: function(){
				var getstore = this.getListjenisabsen().getStore();
				var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
				
				Ext.Ajax.request({
					method: 'POST',
					url: 'c_jenisabsen/export2PDF',
					params: {data: jsonData},
					success: function(response){
						window.open('./temp/jenisabsen.pdf', '_blank');
					}
				});
			},
			
			printRecords: function(){
				var getstore = this.getListjenisabsen().getStore();
				var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
				
				Ext.Ajax.request({
					method: 'POST',
					url: 'c_jenisabsen/printRecords',
					params: {data: jsonData},
					success: function(response){
						var result=eval(response.responseText);
						switch(result){
						case 1:
							win = window.open('./temp/jenisabsen.html','jenisabsen_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
							break;
						default:
							Ext.MessageBox.show({
								title: 'Warning',
								msg: 'Unable to print the grid!',
								buttons: Ext.MessageBox.OK,
								animEl: 'save',
								icon: Ext.MessageBox.WARNING
							});
							break;
						}  
					}
				});
			}
			
		});