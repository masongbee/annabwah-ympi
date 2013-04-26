Ext.define('YMPI.view.MASTER.v_jenisabsen', {
			extend: 'Ext.grid.Panel',
			requires: ['YMPI.store.s_jenisabsen'],
			
			title		: 'jenisabsen',
			itemId		: 'Listjenisabsen',
			alias       : 'widget.Listjenisabsen',
			store 		: 's_jenisabsen',
			columnLines : true,
			frame		: true,
			
			margin		: 0,
			
			initComponent: function(){
				var jenisabsenField = Ext.create('Ext.form.field.Text');
				this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
					  clicksToEdit: 2,
					  clicksToMoveEditor: 1,
					  listeners: {
						  'beforeedit': function(editor, e){
							  console.log(e.record.data.JENISABSEN);
							  if(e.record.data.JENISABSEN != '00'){
								  jenisabsenField.setReadOnly(true);
							  }
							  
						  },
						  'canceledit': function(editor, e){
							  if(e.record.data.ID == 0){
								  editor.cancelEdit();
								  var sm = e.grid.getSelectionModel();
								  e.store.remove(sm.getSelection());
							  }
						  },
						  'validateedit': function(editor, e){
						  },
						  'afteredit': function(editor, e){
							  if(eval(e.record.data.JENISABSEN) < 1){
								  Ext.Msg.alert('Peringatan', 'Kolom "JENISABSEN" tidak boleh "00".');
								  return false;
							  }
							  e.store.sync();
							  return true;
						  }
					  }
				});
				
				this.columns = [
					{ header: 'JENISABSEN',  dataIndex: 'JENISABSEN', field: jenisabsenField },
					{ header: 'KETERANGAN', dataIndex: 'KETERANGAN', field: {xtype: 'textfield'} }];
				this.plugins = [this.rowEditing];
				this.dockedItems = [
					{
						xtype: 'toolbar',
						frame: true,
						items: [{
							text	: 'Add',
							iconCls	: 'icon-add',
							action	: 'create'
						}, {
							itemId	: 'btndelete',
							text	: 'Delete',
							iconCls	: 'icon-remove',
							action	: 'delete',
							disabled: true
						}, '-',{
							text	: 'Export Excel',
							iconCls	: 'icon-excel',
							action	: 'xexcel'
						}, {
							text	: 'Export PDF',
							iconCls	: 'icon-pdf',
							action	: 'xpdf'
						}, {
							text	: 'Cetak',
							iconCls	: 'icon-print',
							action	: 'print'
						}]
					},
					{
						xtype: 'pagingtoolbar',
						store: 's_jenisabsen',
						dock: 'bottom',
						displayInfo: false
					}
				];
				this.callParent(arguments);
			}

		});