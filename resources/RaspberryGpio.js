var OnImg   = 'images/On.jpg';
var OffImg  = 'images/Off.jpg';
var BitOn   = 'images/OkGreen.png';
var BitOff  = 'images/NoRed.png';
var InImg   = 'images/LeftGreen.png';
var OutImg  = 'images/RightRed.png';
var TxRxImg = 'images/TxRxImg.png';

var OnLoad = function() {
    TbPorts = $('#TbPorts').dataTable({
        "searching":        false,
        "paging":           false,
        "info":             false,
        "bDeferRender":     true,
        "order":            [[1, "asc"]]
    });
    TbPorts.fnClearTable();
    TbPorts.fnAddData(TableGPIOArr);

    TbI2C = $('#TbI2C').dataTable({
        "searching":        false,
        "paging":           false,
        "info":             false,
        "bDeferRender":     true,
        "order":            [[0, "asc"]]
    });
    TbI2C.fnClearTable();
    TbI2C.fnAddData(TableI2CArr);

    $.each(PortsGPIOArr, function (index, value) {
        Port    = value[0].toString();
        Mode    = value[1];
        Status  = value[2];

        ImageName = '#StatusImg_' + Port;
        if (Status == 1) {
            $(ImageName).attr('src', OnImg);
        } else {
            $(ImageName).attr('src', OffImg);
        }
        $(ImageName).click(function() {SwitchStatus($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1));});

        ImageName = '#ModeImg_' + Port;
        if (Mode == 'IN') {
            $(ImageName).attr('src', InImg);
            $(ImageName).attr('alt', 'Input');
            $(ImageName).click(function() {SwitchMode($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1));});
        } else if (Mode == 'OUT') {
            $(ImageName).attr('src', OutImg);
            $(ImageName).attr('alt', 'Output');
            $(ImageName).click(function() {SwitchMode($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1));});
        } else {
            $(ImageName).attr('src', TxRxImg);
            $(ImageName).attr('alt', 'Tx-Rx');
        }

        InputName = '#PortNameInput_' + Port;
        $(InputName).change(function() {ModifyUserPortName($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});
    });

    $.each(PortsI2CArr, function (index, value) {
        Device      = value[0].toString();
        Olat        = value[1].toString();
        GPIO_Side   = value[2].toString();
        Port        = value[3];
        UniqID      = value[4].toString();
        Status      = value[5];
        Mode        = 'OUT';

        GPIOImage  = '#GPIOStatusImgI2C_' + UniqID;
        RelayImage = '#RelayStatusImgI2C_' + UniqID;
        if (Status == 1) {
            $(GPIOImage).attr('src', BitOn);
            $(RelayImage).attr('src', OffImg);
        } else {
            $(GPIOImage).attr('src', BitOff);
            $(RelayImage).attr('src', OnImg);
        }
        //$(GPIOImage).click(function() {SwitchStatusI2C($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1));});
        $(RelayImage).click(function() {SwitchStatusI2C($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1));});

        ImageName = '#ModeImgI2C_' + UniqID;
        if (Mode == 'IN') {
            $(ImageName).attr('src', InImg);
            $(ImageName).attr('alt', 'Input');
        } else {
            $(ImageName).attr('src', OutImg);
            $(ImageName).attr('alt', 'Output');
        }
        $(ImageName).click(function() {SwitchModeI2C($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1));});

        InputNameI2C = '#PortNameI2CInput_' + UniqID;
        $(InputNameI2C).change(function() {ModifyUserPortName($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});

        SelectPhaseI2C = '#PhaseSelect_' + UniqID;
        $(SelectPhaseI2C).change(function() {ModifyPhase($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});

        SelectGroupI2C = '#GroupSelect_' + UniqID;
        $(SelectGroupI2C).change(function() {ModifyGroup($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});

        SelectVoltageI2C = '#VoltageSelect_' + UniqID;
        $(SelectVoltageI2C).change(function() {ModifyVoltage($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});

        SelectVoltageGroup01I2C = '#VoltageGroup01Select_' + UniqID;
        $(SelectVoltageGroup01I2C).change(function() {ModifyVoltageGroup01($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});

        SelectVoltageGroup02I2C = '#VoltageGroup02Select_' + UniqID;
        $(SelectVoltageGroup02I2C).change(function() {ModifyVoltageGroup02($(this).attr('id').substring($(this).attr('id').indexOf('_') + 1), $(this).val());});
    });

    VerifyPortsErrors();
};

var GetPortInfo = function(v_nPort) {
    var params = 'PortId=' + v_nPort + '&' + 'GetPortInfo';
    var ImageName;

    $.getJSON('RaspberryGpioActions.php', params, function(json) {

        ImageName = '#StatusImg_' + v_nPort;
        if(json.Return.GetStatus == 1) {
            $(ImageName).attr('src', OnImg);
        } else {
            $(ImageName).attr('src', OffImg);
        }

        ImageName = '#ModeImg_' + v_nPort;
        if(json.Return.GetMode == 'IN') {
            $(ImageName).attr('src', InImg);
            $(ImageName).attr('alt', 'Input');
        } else {
            $(ImageName).attr('src', OutImg);
            $(ImageName).attr('alt', 'Output');
        }

        InputName = '#PortNameInput_' + v_nPort;
        $(InputName).val(json.Return.GetUserPortName);
    });
};

var SwitchStatus = function(v_nPort) {
    var params    = 'PortId=' + v_nPort + '&' + 'SwitchStatus';
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
};

var SwitchMode = function(v_nPort) {
    var params    = 'PortId=' + v_nPort + '&' + 'SwitchMode';
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
};

var ModifyUserPortName = function(v_nPort, v_nNewName) {
    var params    = 'PortId=' + v_nPort + '&' + 'ModifyUserPortName' + '&' + 'NewName=' + v_nNewName;
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
};

var ModifyPhase = function(v_nPort, v_nNewPhase) {
    var params    = 'PortId=' + v_nPort + '&' + 'ModifyPhase' + '&' + 'NewPhase=' + v_nNewPhase;
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
    VerifyPortsErrors();
};

var ModifyGroup = function(v_nPort, v_nNewGroup) {
    var params    = 'PortId=' + v_nPort + '&' + 'ModifyGroup' + '&' + 'NewGroup=' + v_nNewGroup;
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
};

var ModifyVoltage = function(v_nPort, v_nNewVoltage) {
    var params    = 'PortId=' + v_nPort + '&' + 'ModifyVoltage' + '&' + 'NewVoltage=' + v_nNewVoltage;
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
    VerifyPortsErrors();
};

var ModifyVoltageGroup01 = function(v_nPort, v_nNewVoltageGroup01) {
    var params    = 'PortId=' + v_nPort + '&' + 'ModifyVoltageGroup01' + '&' + 'NewVoltageGroup01=' + v_nNewVoltageGroup01;
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
    VerifyPortsErrors();
};

var ModifyVoltageGroup02 = function(v_nPort, v_nNewVoltageGroup02) {
    var params    = 'PortId=' + v_nPort + '&' + 'ModifyVoltageGroup02' + '&' + 'NewVoltageGroup02=' + v_nNewVoltageGroup02;
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfo(v_nPort);
    });
    VerifyPortsErrors();
};

var GetPortInfoI2C = function(v_nPort) {
    var params = 'PortId=' + v_nPort + '&' + 'GetPortInfoI2C';
    var GPIOImage  = '#GPIOStatusImgI2C_' + v_nPort;
    var RelayImage = '#RelayStatusImgI2C_' + v_nPort;

    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        if(json.Return == 1) {
            $(GPIOImage).attr('src', BitOn);
            $(RelayImage).attr('src', OffImg);
        } else {
            $(GPIOImage).attr('src', BitOff);
            $(RelayImage).attr('src', OnImg);
        }
    });
};

var SwitchStatusI2C = function(v_nPort) {
    var params    = 'PortId=' + v_nPort + '&' + 'SwitchStatusI2C';
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfoI2C(v_nPort);
    });
};

var SwitchModeI2C = function(v_nPort) {
    var params    = 'PortId=' + v_nPort + '&' + 'SwitchModeI2C';
    /*
    $.getJSON('RaspberryGpioActions.php', params, function(json) {
        GetPortInfoI2C(v_nPort);
    });
    */
    alert('I2C Input Mode Not Implemented!');
};

var VerifyPortsErrors = function() {
    $.each(PortsI2CArr, function (index, value) {
        UniqID                  = value[4].toString();
        SelectPhaseI2C          = $('#PhaseSelect_'           + UniqID + ' option:selected');
        SelectVoltageI2C        = $('#VoltageSelect_'         + UniqID + ' option:selected');
        SelectVoltageGroup01I2C = $('#VoltageGroup01Select_'  + UniqID + ' option:selected');
        SelectVoltageGroup02I2C = $('#VoltageGroup02Select_'  + UniqID + ' option:selected');
        PhaseDivI2C             = $('#PhaseDivI2C_'           + UniqID);
        VoltageGroup01DivI2C    = $('#VoltageGroup01DivI2C_'  + UniqID);
        VoltageGroup02DivI2C    = $('#VoltageGroup02DivI2C_'  + UniqID);

        PhaseDivI2C.removeClass("PhaseError");
        VoltageGroup01DivI2C.removeClass("PhaseError");
        VoltageGroup02DivI2C.removeClass("PhaseError");

        PortL1  = UniqID;
        PhaseL1 = SelectPhaseI2C.val();
        if ((SelectVoltageI2C.text() != VoltageArray[0]) && (SelectVoltageI2C.text() != VoltageArray[1])) {
            PortL2  = SelectVoltageGroup01I2C.val();
            PhaseL2 = $('#PhaseSelect_' + PortL2 + ' option:selected').val();
            if(PhaseL2 == PhaseL1) {
                PhaseDivI2C.addClass("PhaseError");
                VoltageGroup01DivI2C.addClass("PhaseError");
                alert('Phase conflict in ports: "'+PortL1+'" and "'+PortL2+'"');
            }

            if (SelectVoltageI2C.text() != VoltageArray[2]) {
                PortL3  = SelectVoltageGroup02I2C.val();
                PhaseL3 = $('#PhaseSelect_' + PortL3 + ' option:selected').val();
                if(PhaseL3 == PhaseL1) {
                    PhaseDivI2C.addClass("PhaseError");
                    VoltageGroup02DivI2C.addClass("PhaseError");
                    alert('Phase conflict in ports: "'+PortL1+'" and "'+PortL3+'"');
                }
                //console.log('PortL1: '+PortL1+' - PhaseL1: '+PhaseL1+' | PortL2: '+PortL2+' - PhaseL2: '+PhaseL2+' | PortL3: '+PortL3+' - PhaseL3: '+PhaseL3)
            }
        }
    });
};
