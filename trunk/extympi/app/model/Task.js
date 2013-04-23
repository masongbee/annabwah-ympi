Ext.define('YMPI.model.Task', {
    extend: 'Ext.data.Model',
    idProperty: 'NIK',
    fields: [
        {name: 'NIK', type: 'string'},
        {name: 'NAMAUNIT', type: 'string'},
        {name: 'NAMA', type: 'string'},
        {name: 'TL', type: 'date', dateFormat:'m/d/Y'},
        {name: 'TGLMASUK', type: 'date', dateFormat:'m/d/Y'},
        {name: 'MASAKERJA', type: 'int'},
        {name: 'ALAMAT', type: 'string'}
    ]
});
