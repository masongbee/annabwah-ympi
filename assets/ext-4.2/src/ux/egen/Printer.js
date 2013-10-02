/**
 * @class Ext.ux.egen.Printer
 * @author EkoJs
 * Helper class to easily print the contents of a grid. Will open a new window with a table where the first row
 * contains the headings from your column model, and with a row for each item in your grid's store. When formatted
 * with appropriate CSS it should look very similar to a default grid. If renderers are specified in your column
 * model, they will be used in creating the table. Override headerTpl and bodyTpl to change how the markup is generated
 * 
 * Usage:
 * 
 * 1 - Add Ext.Require Before the Grid code
 * Ext.require([
 *   'Ext.ux.egen.Printer',
 * ]);
 * 
 * 2 - Declare the Grid 
 * var grid = Ext.create('Ext.grid.Panel', {
 *   columns: //some column model,
 *   store   : //some store
 * });
 * 
 * 3 - Print!
 * Ext.ux.egen.Printer.mainTitle = 'Your Title here'; //optional
 * Ext.ux.egen.Printer.print(grid);
 * 
 * Original url: http://edspencer.net/2009/07/printing-grids-with-ext-js.html
 */
Ext.define("Ext.ux.egen.Printer", {    
    requires: 'Ext.XTemplate',
    statics: {
        /**
         * Prints the passed grid. Reflects on the grid's column model to build a table, and fills it using the store
         * @param {Ext.grid.Panel} grid The grid to print
         */
        print: function(grid) {
			var kelompok = this.getGroupedData(grid);
			
			//kelompok.groupField
			//kelompok.columns
			//kelompok.groups
			//kelompok.groupData
			//kelompok.fitur
			//kelompok.groupRecords			
			
            /*var columns = [];
            //account for grouped columns
            Ext.each(grid.columns, function(c) {
				if(c.text != null || c.text != '') {
                    columns.push(c);
                }
            });*/
			//console.info(columns);
			
			//remove columns that do not contains dataIndex or dataIndex is empty. for example: columns filter or columns button
            var clearColumns = [];
            Ext.each(kelompok.columns, function (column) {
                if ((column) && (!Ext.isEmpty(column.dataIndex) && !column.hidden)) {
                    clearColumns.push(column);
                } else	if (column && column.xtype === 'rownumberer'){
					column.text = 'Row';
					clearColumns.push(column);
				}
            });
            kelompok.columns = clearColumns;
			console.info(kelompok);
			//console.info(clearColumns);
			/*
			var bodyTplfn = new Ext.XTemplate(
				'<tr>',
				  '<td colspan=\'{[this.getColumnCount()]}\'>',
					'<div class=\'group-header\'>{[this.getGroupTextTemplate()]}</div>',
					'<table class=\'group-body\'>',
					  '{[this.getInnerTemplate()]}',
					'</table>',
					'{[this.getGroupSummaryTemplate()]}',
				  '</td>',
				'</tr>',

				{
					numColumns: 0,
					cellTpl: new Ext.XTemplate('<tpl for="."><td style=\'{style}\'>\{{dataIndex}\}</td></tpl>'),
					groupSummaryCellTemplate: new Ext.XTemplate('<tpl for="."><td style=\'{style}\'>\{{dataIndex}\}</td></tpl>'),
					innerTemplate: null,
					groupSummaryTemplate: null,

					getColumnCount: function() {
						return (this.numColumns);
					},

					getGroupTextTemplate: function() {
						return ('{groupText}');
					},

					getInnerTemplate: function() {
						return (this.innerTemplate);
					},

					getGroupSummaryTemplate: function() {
						return (this.groupSummaryTemplate);
					}
				});
			
			var generateBody;
			if(fitur.grid)
			{
				if(fitur.groupingsummary)
				{
					bodyTplfn.numColumns = columns.length;
					var cells = bodyTplfn.cellTpl.apply(columns);
					bodyTplfn.innerTemplate = Ext.String.format('<tpl for="groupRecords"><tr>{0}</tr></tpl>', cells);
					
					console.info(cells);
					
					if (grid.hasPlugin(Ext.grid.GroupSummary)) {
						var summaryCells = bodyTplfn.groupSummaryCellTemplate.apply(columns);
						bodyTplfn.groupSummaryTemplate = Ext.String.format('<table class=\'group-summary\'><tpl for="summaries"><tr>{0}</tr></tpl></table>', summaryCells);
					} else {
						bodyTplfn.groupSummaryTemplate = '';
					}

					var headings = Ext.create('Ext.XTemplate', this.headerTpl).apply(columns);
					var body = bodyTplfn.apply({});

					generateBody = (Ext.String.format('<table class=\'table-parent\'>{0}<tpl for=".">{1}</tpl></table>', headings, body));
					console.info(generateBody);
				}
			}*/

			
            //build a usable array of store data for the XTemplate
            var data = [];
            grid.store.data.each(function(item, row) {
				var convertedData = {};
				convertedData['groupText'] = grid.store.groupField;
                //apply renderers from column model
                for (var key in item.data) {
                    var value = item.data[key];
					
                    Ext.each(kelompok.columns, function(column, col) {
                        if (column && column.dataIndex == key) {
                            /*
                             * TODO: add the meta to template
                             */
                            var meta = {item: '', tdAttr: '', style: ''};
                            value = column.renderer ? column.renderer.call(grid, value, meta, item, row, col, grid.store, grid.view) : value;
                            //convertedData[Ext.String.createVarName(column.text)] = value;
                            convertedData[column.dataIndex] = value;
							//console.info(convertedData);
                        } else if (column && column.xtype === 'rownumberer'){
							convertedData['Row'] = row + 1;
						}
                    }, this);
                }

                data.push(convertedData);
            });
			
			//console.info(data);
			
			var dataku = [];
			
			for(var i = 0;i < kelompok.groupData.length;i++)
			{
				var n = {
					name:kelompok.groupData[i].name
				};
				dataku.push(n);
				for(var j = 0;j < kelompok.groupData[i].records.length;j++)
				{
					var g = {
						groupRecords :kelompok.groupData[i].records[j].data
					};
					dataku.push(g);
				}
			}
			console.info(dataku);
			
            //get Styles file relative location, if not supplied
            if (this.cssPath === null) {
                var scriptPath = Ext.Loader.getPath('Ext.ux.egen.Printer');
                this.cssPath = scriptPath.substring(0, scriptPath.indexOf('Printer.js')) + 'css/Ext.ux.Printer.css';
            }
			/*
            //use the headerTpl and bodyTpl markups to create the main XTemplate below
            var headings = Ext.create('Ext.XTemplate', this.headerTpl).apply(columns);
            var body     = Ext.create('Ext.XTemplate', this.bodyTpl).apply(columns);
            var pluginsBody = '',
                pluginsBodyMarkup = [];
            
            //add relevant plugins
            Ext.each(grid.plugins, function(p) {
                if (p.ptype == 'rowexpander') {
                    pluginsBody += p.rowBodyTpl.join('');
                }
            });
            
            if (pluginsBody != '') {
                pluginsBodyMarkup = [
                    '<tr class="{[xindex % 2 === 0 ? "even" : "odd"]}"><td colspan="' + columns.length + '">',
                      pluginsBody,
                    '</td></tr>'
                ];
            }
            
            //Here because inline styles using CSS, the browser did not show the correct formatting of the data the first time that loaded
			
            var htmlMarkup = [
                '<!DOCTYPE html>',
                '<html class="' + Ext.baseCSSPrefix + 'ux-grid-printer">',
                  '<head>',
                    '<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />',
                    '<link href="' + this.cssPath + '" rel="stylesheet" type="text/css" />',
                    '<title>' + grid.title + '</title>',
                  '</head>',
                  '<body class="' + Ext.baseCSSPrefix + 'ux-grid-printer-body">',
                  '<div class="x-ux-grid-printer">',
                      '<a class="' + Ext.baseCSSPrefix + 'ux-grid-printer-linkprint" href="javascript:void(0);" onclick="window.print();">' + this.printLinkText + '</a>',
                      '<a class="' + Ext.baseCSSPrefix + 'ux-grid-printer-linkclose" href="javascript:void(0);" onclick="window.close();">' + this.closeLinkText + '</a>',
                  '</div>',
                  '<h1>' + this.mainTitle + '</h1>',
                    '<table>',
                      '<tr>',
                        headings,
                      '</tr>',
                      '<tpl for=".">',
                        '<tr class="{[xindex % 2 === 0 ? "even" : "odd"]}">',
                          body,
                        '</tr>',
                        pluginsBodyMarkup.join(''),
                      '</tpl>',
                    '</table>',
                  '</body>',
                '</html>'           
            ];*/
			
			var htmlMarkup = [
				'<!DOCTYPE html>',
				'<html>',
				'<head>',
					'<link href="' + this.cssPath + '" rel="stylesheet" type="text/css" media="screen,print" />',
					'<title>' + this.mainTitle + '</title>',
				'</head>',
				'<body class="' + Ext.baseCSSPrefix + 'ux-grid-printer-body">',
				'<div class="x-ux-grid-printer">',
					'<a class="' + Ext.baseCSSPrefix + 'ux-grid-printer-linkprint" href="javascript:void(0);" onclick="window.print();">' + this.printLinkText + '</a>',
					'<a class="' + Ext.baseCSSPrefix + 'ux-grid-printer-linkclose" href="javascript:void(0);" onclick="window.close();">' + this.closeLinkText + '</a>',
				'</div>',
					this.generateBody(grid),
				'</body>',
				'</html>'
			];
			
            var html = Ext.create('Ext.XTemplate', htmlMarkup).apply(dataku);
			
			/*var html = new Ext.XTemplate(
				  '<!DOCTYPE html>',
				  '<html>',
					'<head>',
					  '<link href="' + this.cssPath + '" rel="stylesheet" type="text/css" media="screen,print" />',
					  '<title>' + this.mainTitle + '</title>',
					'</head>',
					'<body>',
						'<div class="x-ux-grid-printer">',
							'<a class="' + Ext.baseCSSPrefix + 'ux-grid-printer-linkprint" href="javascript:void(0);" onclick="window.print();">' + this.printLinkText + '</a>',
							'<a class="' + Ext.baseCSSPrefix + 'ux-grid-printer-linkclose" href="javascript:void(0);" onclick="window.close();">' + this.closeLinkText + '</a>',
						'</div>',
					generateBody,
					'</body>',
				  '</html>'
				).apply(data);*/
			
			console.info(htmlMarkup);
			console.info(html);

            //open up a new printing window, write to it, print it and close
            var win = window.open('', 'printgrid');
            
            //document must be open and closed
            win.document.open();
            win.document.write(html);
            win.document.close();
            
            if (this.printAuto){
                win.print();
            }
            
            //Another way to set the closing of the main
            if (this.closeAfterPrint){
                if(Ext.isIE){
                    window.close();
                } else {
                    win.close();
                }                
            }
        },
		
		getGroupedData: function(grid) {
			var xtypes = grid.getXType();
			var columns = grid.columns;
			var rs = grid.store.getRange();
			var ds = grid.store;
			var view = grid.view;
			
			//console.info(rs);
			//console.info(ds);
			//console.info(view);
			//console.info(columns);
			
			var groupField = ds.getGroupField();
			var groups = ds.getGroups();
			var groupData = ds.getGroupData();
			var kelompok = new Object();
			
			kelompok.groupField = groupField;
			kelompok.columns = columns;
			kelompok.groups = groups;
			kelompok.groupData = groupData;
			
			//console.info(groupField);
			
			var features = grid.features;
			var lsfitur = new Array();
			var fitur = new Object();
				fitur.grouping = false;
				fitur.groupingsummary = false;
				fitur.grid = (xtypes == 'gridpanel' ? true : false);
			
			for (var i = features.length - 1; i >= 0; i--) {
                var feature = features[i].ftype;
				lsfitur[i] = feature;
				if(feature == 'grouping')
				{
					fitur.grouping = true;
				}
				else if(feature == 'groupingsummary')
				{
					fitur.groupingsummary = true;
				}
            }
			
			kelompok.fitur = fitur;
			var gRecords = []
			
			Ext.each(groupData, function(group) {
				var groupRecords = [];				
				//console.info(group.name);

				Ext.each(group.records, function(item) {
					var convertedData = {};
					
					//Cek GroupData.dataIndex dengan kolom.dataIndex
					Ext.iterate(item.data, function(key, value) {
						Ext.each(columns, function(column) {
							if (column.dataIndex == key) {
								convertedData[key] = value;
								return false;
							}
						}, this);
					});

					//groupRecords.push(convertedData);
					gRecords.push(convertedData);
				});				

				//group.groupRecords = groupRecords;
				//gRecords.push(groupRecords);
				
				//console.info(grid.findPlugin());
				//console.info(view.getColumnData());
				
				/*if (fitur.groupingsummary) {
					//Summary calculation for column in each group.
					var cs = view.getColumnData();
					group.summaries = {};
					var data = summaryRenderer.calculate(group.rs, cs);

					Ext.each(columns, function(col) {
						var rendered = '';
						if (col.summaryType || col.summaryRenderer) {
							rendered = (col.summaryRenderer || col.renderer)(data[col.name], {}, { data: data }, 0, col.actualIndex, grid.store);
						}
						if (rendered == undefined || rendered === "") rendered = "&#160;";

						group.summaries[col.dataIndex] = rendered;
					});
				}*/

				//delete group.rs;
			});
			
			kelompok.groupRecords = gRecords;
			//console.info(kelompok);
			return kelompok;
		},
		
		generateBody: function(grid) {
			
			//kelompok.groupField
			//kelompok.columns
			//kelompok.groups
			//kelompok.groupData
			//kelompok.fitur
			//kelompok.groupRecords
			
			var kelompok = this.getGroupedData(grid),hasil;
			//console.info(kelompok);
			
			var view = grid.view;

			if (kelompok.fitur.groupingsummary || kelompok.fitur.grouping) {
				//this.bodyTpl.groupName = Ext.String.format('\{{0}\}', kelompok.groupField);
				this.bodyTpl.numColumns = kelompok.columns.length;
				var cells = this.bodyTpl.cellTpl.apply(kelompok.columns);
				this.bodyTpl.innerTemplate = Ext.String.format('<tpl for="groupRecords"><tr>{0}</tr></tpl>', cells);

				if (kelompok.fitur.groupingsummary) {
					var summaryCells = this.bodyTpl.groupSummaryCellTemplate.apply(kelompok.columns);
					this.bodyTpl.groupSummaryTemplate = Ext.String.format('<table class=\'group-summary\'><tpl for="summaries"><tr>{0}</tr></tpl></table>', summaryCells);
				} else {
					this.bodyTpl.groupSummaryTemplate = '';
				}

				var headings = Ext.create('Ext.XTemplate', this.headerTpl).apply(kelompok.columns);
				var body = this.bodyTpl.apply({});

				hasil = (Ext.String.format('<table class=\'table-parent\'>{0}<tpl for=".">{1}</tpl></table>', headings, body));

			} else {
				//No grouping, use base class logic.
				//return (Ext.ux.Printer.GroupedGridPanelRenderer.superclass.generateBody.call(this, grid));
			}
			return hasil;
			//console.info(hasil);
		},

        /**
         * @property cssPath
         * @type String
         * The path at which the print stylesheet can be found (defaults to 'ux/egen/css/Ext.ux.Printer.css')
         */
        cssPath: null,
        
        /**
         * @property printAuto
         * @type Boolean
         * True to open the print dialog automatically and close the window after printing. False to simply open the print version
         * of the grid (defaults to false)
         */
        printAuto: false,
        
        /**
         * @property closeAfterPrint
         * @type Boolean
         * True to close the window automatically after printing.
         * (defaults to false)
         */
        closeAfterPrint: false,        
        
        /**
         * @property mainTitle
         * @type String
         * Title to be used on top of the table
         * (defaults to empty)
         */
        mainTitle: '',
        
        /**
         * Text show on print link
         * @type String
         */
        printLinkText: 'Print',
        
        /**
         * Text show on close link
         * @type String
         */
        closeLinkText: 'Close',
        
        /*
         * @property headerTpl
         * @type {Object/Array} values
         * The markup used to create the headings row. By default this just uses <th> elements, override to provide your own
         *
        headerTpl: [ 
            '<tpl for=".">',
                '<th>{text}</th>',
            '</tpl>'
        ],

        /**
         * @property bodyTpl
         * @type {Object/Array} values
         * The XTemplate used to create each row. This is used inside the 'print' function to build another XTemplate, to which the data
         * are then applied (see the escaped dataIndex attribute here - this ends up as "{dataIndex}")
         *
        bodyTpl: [
            '<tpl for=".">',
                '<td>\{{dataIndex}\}</td>',
            '</tpl>'
        ]*/
		headerTpl: [
			'<tr>',
			  '<tpl for=".">',
				'<th style=\'{style}\'>{text}</th>',
			  '</tpl>',
			'</tr>'
		],

		bodyTpl: new Ext.XTemplate(
			'<tr>',
			  '<td colspan=\'{[this.getColumnCount()]}\'>',
				'<div class=\'group-header\'>{[this.getGroupTextTemplate()]}</div>',
				'<table class=\'group-body\'>',
				  '{[this.getInnerTemplate()]}',
				'</table>',
				'{[this.getGroupSummaryTemplate()]}',
			  '</td>',
			'</tr>',

			{
				numColumns: 0,
				cellTpl: new Ext.XTemplate('<tpl for="."><td style=\'{style}\'>\{{dataIndex}\}</td></tpl>'),
				groupSummaryCellTemplate: new Ext.XTemplate('<tpl for="."><td style=\'{style}\'>\{{dataIndex}\}</td></tpl>'),
				innerTemplate: null,
				groupSummaryTemplate: null,
				groupName: null,

				getColumnCount: function() {
					return (this.numColumns);
				},

				getGroupTextTemplate: function() {
					//return (this.groupName);
					return ('{name}');
				},

				getInnerTemplate: function() {
					return (this.innerTemplate);
				},

				getGroupSummaryTemplate: function() {
					return (this.groupSummaryTemplate);
				}
			}),
    }
});
