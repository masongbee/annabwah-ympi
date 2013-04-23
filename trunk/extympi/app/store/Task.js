Ext.define('YMPI.store.Task', {
    extend	: 'Ext.data.Store',
    model	: 'YMPI.model.Task',
    
    autoLoad	: true,
    autoSync	: false,
    
    data: [
           {NAMAUNIT: 'DEPARTEMEN GA', NIK: '11000.001', NAMA: 'ANTON', TL: '06/24/1971', TGLMASUK:'06/24/2007', MASAKERJA: 23, ALAMAT: 'PANDAAN'},
           {NAMAUNIT: 'DEPARTEMEN GA', NIK: '11000.002', NAMA: 'DICKY', TL: '06/24/1972', TGLMASUK:'06/25/2007', MASAKERJA: 23, ALAMAT: 'PANDAAN'},
           {NAMAUNIT: 'DEPARTEMEN HR', NIK: '12000.001', NAMA: 'UDIN', TL: '06/24/1973', TGLMASUK:'06/27/2007', MASAKERJA: 22, ALAMAT: 'GEMPOL'},
           {NAMAUNIT: 'DEPARTEMEN HR', NIK: '12000.002', NAMA: 'SUSI', TL: '06/24/1974', TGLMASUK:'06/29/2007', MASAKERJA: 22, ALAMAT: 'PANDAAN'}
       ],
    sorters: {property: 'TGLMASUK', direction: 'ASC'},
    groupField: 'NAMAUNIT',
    
    constructor: function(){
    	this.callParent(arguments);
    }
    
});
