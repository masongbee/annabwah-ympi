/*Ext.define('YMPI.Utilities', {
    statics: {
        jsTrim: function (str) {
        	str = str.replace(/^\s+/, '');
            for (var i = str.length - 1; i >= 0; i--) {
                if (/\S/.test(str.charAt(i))) {
                    str = str.substring(0, i + 1);
                    break;
                }
            }
            return str;
        }
    }
});*/

Ext.define('Ext.util.Format.comboRenderer', function(combo){
	return function(value){
		var record = combo.findRecord(combo.valueField, value);
		return record ? record.get(combo.displayField) : combo.valueNotFoundText;
	}
});

Ext.define('YMPI.controller.Main', {
    extend: 'Ext.app.Controller',
    
    stores: [
        'Examples'
    ],

    views: [
        'Viewport',
        'Header'
    ],

    refs: [
        {
            ref: 'examplePanel',
            selector: '#examplePanel'
        },
        {
            ref: 'exampleList',
            selector: 'exampleList'
        }
    ],

    init: function() {
        this.control({
            'viewport exampleList': {
                'select': function(me, record, item, index, e) {
                    /*if (!record.isLeaf()) {
                        return;
                    }

                    this.setActiveExample(this.classNameFromRecord(record), record.get('text'));*/
                	
                    if(!record.get('id')){
                		return;
                	}
                    this.setActiveExample(this.classNameFromRecord(record), record.get('id'));
                }/*,
                afterrender: function(){
                    var me = this,
                        className, exampleList, name, record;

                    setTimeout(function(){
                        className = location.hash.substring(1);
                        exampleList = me.getExampleList();

                        if (className) {
                            name = className.replace('-', ' ');
                            record = exampleList.view.store.find('text', name);     
                        } else {
							record = exampleList.view.store.find('text', 'grade');
						}

                        exampleList.view.select(record);
                    }, 0);
                }*/
            }
        });
    },

    setActiveExample: function(className, title) {
        var examplePanel = this.getExamplePanel(),
            path, example, className;
        
        if (!title) {
            title = className.split('.').reverse()[0];
        }
        
        //update the title on the panel
        //examplePanel.setTitle(title);
        
        //remember the className so we can load up this example next time
        location.hash = title.toLowerCase().replace(' ', '-');

        //set the browser window title
        document.title = document.title.split(' - ')[0] + ' - ' + title;
        
        //create the example
        example = Ext.create(className);
        
        //remove all items from the example panel and add new example
        examplePanel.removeAll();
        examplePanel.add(example);
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
