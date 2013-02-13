Ext.define('YMPI.view.List', {
    extend: 'Ext.tree.Panel',
    xtype: 'exampleList',
    
    requires: [
        'YMPI.store.Examples'
    ],
    
    title: 'Examples',
    rootVisible: false,
	
	cls: 'examples-list',
    
    lines: false,
    useArrows: true,
    
    store: 'Examples'
});
