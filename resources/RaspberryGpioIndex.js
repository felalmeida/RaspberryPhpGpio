var OnImg   = 'images/On.jpg';
var OffImg  = 'images/Off.jpg';
var BitOn   = 'images/OkGreen.png';
var BitOff  = 'images/NoRed.png';
var InImg   = 'images/LeftGreen.png';
var OutImg  = 'images/RightRed.png';
var TxRxImg = 'images/TxRxImg.png';

var OnLoad = function() {
    TbDevices = $('#TbDevices').dataTable({
        "searching":        false,
        "paging":           false,
        "info":             false,
        "bDeferRender":     true,
        "order":            [[1, "asc"]]
    });
    TbDevices.fnClearTable();
    TbDevices.fnAddData(TbDevicesArr);
};
