Ext.define('YMPI.controller.Main', {
    extend: 'Ext.app.Controller',

    stores: [
        'Examples', 's_jabatan_pure'
    ],

    refs: [
        {
            ref: 'viewport',
            selector: 'viewport'
        },
        {
            ref: 'navigation',
            selector: 'navigation'
        },
        {
            ref: 'contentPanel',
            selector: '#contentPanel'
        }
    ],

    exampleRe: /^\s*\/\/\s*(\<\/?example>)\s*$/,

    init: function() {
        this.control({
            'navigation': {
                selectionchange: 'onNavSelectionChange'
            },
            /*'viewport': {
                afterlayout: 'afterViewportLayout'
            },*/
            'contentPanel': {
                resize: 'centerContent'
            }
        });
    },

    /*afterViewportLayout: function() {
        if (!this.navigationSelected) {
            var id = location.hash.substring(1),
                navigation = this.getNavigation(),
                store = navigation.getStore(),
                node;

            node = id ? store.getNodeById(id) : store.getRootNode().firstChild.firstChild;

            navigation.getSelectionModel().select(node);
            navigation.getView().focusNode(node);
            this.navigationSelected = true;
        }
    },*/

    onNavSelectionChange: function(selModel, records) {
        var record = records[0],
            text = record.get('text'),
            xtype = record.get('id'),
            alias = 'widget.' + xtype,
            contentPanel = this.getContentPanel(),
            cmp;

        /*if (xtype) { // only leaf nodes have ids
            contentPanel.removeAll(true);

            var className = Ext.ClassManager.getNameByAlias(alias);
            var ViewClass = Ext.ClassManager.get(className);
            var clsProto = ViewClass.prototype;
            if (clsProto.themes) {
                clsProto.themeInfo = clsProto.themes[Ext.themeName] || clsProto.themes.classic;
            }

            cmp = new ViewClass();
            contentPanel.add(cmp);
            if (cmp.floating) {
                cmp.show();
            } else {
                this.centerContent();
            }

            contentPanel.setTitle(text);

            document.title = document.title.split(' - ')[0] + ' - ' + text;
            location.hash = xtype;
        }*/
		
		console.info(xtype);
		if(!xtype){
			return;
		}
		else if(xtype == "LOGOUT")
		{
			var redirect = '';
			Ext.Ajax.request({
				url: 'c_action/logout',
				success: function(response){
					//redirect = 'home';
					//window.location = redirect;
					location.reload();
				}
			});
		}else{
			this.setActiveExample(this.classNameFromRecord(record), record.get('id'));
		}
		else
			this.setActiveExample(this.classNameFromRecord(record), record.get('id'));
    },

    centerContent: function() {
        var contentPanel = this.getContentPanel(),
            body = contentPanel.body,
            item = contentPanel.items.getAt(0),
            align = 'c-c',
            overflowX,
            overflowY,
            offsets;

        if (item) {
            overflowX = (body.getWidth() < (item.getWidth() + 40));
            overflowY = (body.getHeight() < (item.getHeight() + 40));

            if (overflowX && overflowY) {
                align = 'tl-tl',
                offsets = [20, 20];
            } else if (overflowX) {
                align = 'l-l';
                offsets = [20, 0];
            } else if (overflowY) {
                align = 't-t';
                offsets = [0, 20];
            }

            item.alignTo(contentPanel.body, align, offsets);
        }
    },
	
	setActiveExample: function(className, title) {
        var contentPanel = this.getContentPanel(),
            path, example, className;
        
        if (!title) {
            title = className.split('.').reverse()[0];
        }
        
        //update the title on the panel
        //contentPanel.setTitle(title);
        
        //remember the className so we can load up this example next time
        location.hash = title.toLowerCase().replace(' ', '-');

        //set the browser window title
        document.title = document.title.split(' - ')[0] + ' - ' + title;
        
        //create the example
        example = Ext.create(className);
        
        //remove all items from the example panel and add new example
        contentPanel.removeAll(true);
        contentPanel.add(example);
    },
    
    // Will be used for source file code
    // loadExample: function(path) {
    //     Ext.Ajax.request({
    //         url: path,
    //         success: function() {
    //             console.log(Ext.htmlEncode(response.responseText));
    //         }
    //     });
    // },

    filePathFromRecord: function(record) {
        var parentNode = record.parentNode,
            path = record.get('id');
        
        while (parentNode && parentNode.get('text') != "Root") {
            path = parentNode.get('text') + '/' + Ext.String.capitalize(path);

            parentNode = parentNode.parentNode;
        }

        return this.formatPath(path);
    },

    classNameFromRecord: function(record) {
        var path = this.filePathFromRecord(record);

        path = 'YMPI.view.' + path.replace('/', '.');

        return path;
    },

    formatPath: function(string) {
        /*var result = string.split(' ')[0].charAt(0).toLowerCase() + string.split(' ')[0].substr(1),
            paths = string.split(' '),
            ln = paths.length,
            i;

        for (i = 1; i < ln; i++) {
            result = result + Ext.String.capitalize(paths[i]);
        }*/
    	var result = string.split(' ')[0].charAt(0) + string.split(' ')[0].substr(1),
	        paths = string.split(' '),
	        ln = paths.length,
	        i;

	    for (i = 1; i < ln; i++) {
	        result = result + Ext.String.capitalize(paths[i]);
	    }
	    
        return result;
    }
});
