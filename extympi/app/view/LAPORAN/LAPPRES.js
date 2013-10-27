Ext.define('YMPI.view.LAPORAN.LAPPRES', {
	extend: 'Ext.grid.Panel',
	
	requires	: ['Ext.grid.*',
		'Ext.data.*',
		'Ext.form.field.Number',
		'Ext.form.field.Date',
		'Ext.tip.QuickTipManager'],
	
	itemId		: 'LAPPRES',
    alias       : 'widget.LAPPRES',
	//store 		: 's_importpres',
    
    title		: 'Daftar Presensi',
    columnLines : true,
    frame		: true,
    margins		: 0,
    
    initComponent: function(){
		var me = this;
    	this.store = Ext.create('YMPI.store.s_rptpresensi');
    	this.features = [{
				ftype: 'filters',
				autoReload: true,
				encode: true,
				local: false
			},{
            id: 'group',
            ftype: 'groupingsummary',
			//startCollapsed: false
            //groupHeaderTpl: '{name}'
            //hideGroupedHeader: true,
            //enableGroupingMenu: false
        }];
        this.columns = [{
            header: 'TANGGAL',
            width: 180,
            dataIndex: 'TANGGAL',
            renderer: Ext.util.Format.dateRenderer('m/d/Y'), filterable: true
        }, {
            header: 'No. NIK',
            width: 180,
            dataIndex: 'NIK', filterable: true
			/*summaryType: 'count',
			summaryRenderer: function(value){
				return Ext.String.format('Total {0} karyawan', value);
			}*/
        }, {
            header: 'NAMA',
            flex: 1,
            dataIndex: 'NAMAKAR', filterable: true
        }, {
            header: 'NAMA UNIT',
            width: 80,
            dataIndex: 'NAMAUNIT', filterable: true
        }, {
            header: 'BAGIAN',
            width: 80,
            dataIndex: 'SINGKATAN', filterable: true
        }, {
            header: 'NAMA SHIFT',
            width: 75,
            dataIndex: 'NAMASHIFT', filterable: true
        }, {
            header: 'SHIFT',
            width: 75,
            dataIndex: 'SHIFTKE', filterable: true
        }];
        
        this.dockedItems = [Ext.create('Ext.form.Panel', {
            bodyPadding: 5,
            items: [{
				xtype:'fieldcontainer',
                layout: 'hbox',
				items:[{
                	xtype: 'button',
                	//text	: 'Export Excel',
                    iconCls	: 'icon-print',
                    scale   : 'small',
					handler	: function(){
						Ext.ux.egen.Printer.mainTitle = 'Laporan Presensi';
						Ext.ux.egen.Printer.printAuto = false;
						Ext.ux.egen.Printer.print(me);
						
						console.info(me);
						//Ext.ux.egen.Printer.generateBody(me);
					}
                }, {
                    xtype: 'splitter'
                },{
                	xtype: 'button',
                	//text	: 'Export Excel',
                    iconCls	: 'icon-excel',
                    action	: 'xexcel',
                    scale   : 'small'
                }, {
                    xtype: 'splitter'
                }, {
                	xtype: 'button',
                	//text	: 'Export PDF',
                    iconCls	: 'icon-pdf',
                    action	: 'xpdf',
                    scale   : 'small'
                }]
			}]
            //renderTo: Ext.getBody()
        })];
        
        this.callParent(arguments);
    }

});