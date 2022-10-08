<?php
include_once("RaspberryGpio.php");

$PortsGPIOArr   = array();
$TableGPIOArr   = array();
$PortsI2CArr    = array();
$TableI2CArr    = array();
$I2CDevices     = GpioPort::GetI2CDevices();
$I2CStatus      = GpioPort::ReadI2CStatus();

foreach ($PortsArray as $nKey => $Port) {
    $PhysicalId = $Port['PhysicalId'];
    $PortId     = $Port['PortId'];
    $PortName   = $Port['PortName'];
    $PortObj    = new GpioPort($PortId);

    ### UserPortName
    $UserPortNameBody = "";
    if (isset($PortObj->UserPortName)) {
        $UserPortNameBody = "<input id='PortNameInput_".$PortId."' class='PortNameInputClass' type='text' value='".$PortObj->UserPortName."'>";
    } else {
        $UserPortNameBody = "<input id='PortNameInput_".$PortId."' class='PortNameInputClass' type='text'>";
    }

    $ButtonStatus   = "<div id='StatusDiv_".$PortId."' class='StatusDivClass'>  <img id='StatusImg_".$PortId."' src='' height='13px;'></div>";
    $ButtonMode     = "<div id='ModeDiv_".$PortId."'   class='ModeDivClass'>    <img id='ModeImg_".$PortId."'   src='' height='13px;'></div>";
    $UserPortName   = "<div id='PortName_".$PortId."'  class='PortNameDivClass'>".$UserPortNameBody."</div>";

    array_push($PortsGPIOArr, array($PortId,$PortObj->Mode,$PortObj->Status));
    array_push($TableGPIOArr, array($PhysicalId,$PortId,$PortName,$ButtonMode,$ButtonStatus,$UserPortName));
}

foreach ($I2CDevices as $nDevKey => $Dev) {
    foreach ($I2CArray['OLATS'] as $nOlatKey => $Olat) {
        $GPIO_Side      = ($nOlatKey == 0) ? "A" : "B";
        $nIntReturn     = intval($I2CStatus[$Dev."-".$Olat]);
        $sRevBinReturn  = strrev(GpioPort::IntToBin($nIntReturn));

        for ($nPort = 0; $nPort < 8; $nPort++) {
            $sUniqID    = $Dev.'-'.$Olat.'-'.$GPIO_Side.'-'.$nPort;
            $PortObj    = new GpioPort($sUniqID);

            ### Phase
            $PhaseContent = "";
            foreach ($PhaseArray as $nPhaseKey => $PhaseValue) {
                $PhaseSelected = '';
                if((isset($PortObj->PortPhase)) and ($PortObj->PortPhase == $nPhaseKey)) {$PhaseSelected = 'selected';}
                $PhaseContent .= "<option value='".$nPhaseKey."' ".$PhaseSelected.">".$PhaseValue."</option>";
            }
            $PhaseBody = "<select id='PhaseSelect_".$sUniqID."' class='PhaseSelectClass'>".$PhaseContent."</select>";

            ### UserPortName
            $UserPortNameBody = "";
            if (isset($PortObj->UserPortName)) {
                $UserPortNameBody = "<input id='PortNameI2CInput_".$sUniqID."'  class='PortNameInputClass' type='text' value='".$PortObj->UserPortName."'>";
            } else {
                $UserPortNameBody = "<input id='PortNameI2CInput_".$sUniqID."'  class='PortNameInputClass' type='text'>";
            }

            ### Group
            $GroupContent = "";
            foreach ($GroupsArray as $nGroupKey => $GroupValue) {
                $GroupSelected = '';
                if((isset($PortObj->PortHomeGroup)) and ($PortObj->PortHomeGroup == $nGroupKey)) {$GroupSelected = 'selected';}
                $GroupContent .= "<option value='".$nGroupKey."' ".$GroupSelected.">".$GroupValue."</option>";
            }
            $GroupBody = "<select id='GroupSelect_".$sUniqID."' class='GroupSelectClass'>".$GroupContent."</select>";
            
            ### Voltage
            $VoltageContent = "";
            foreach ($VoltageArray as $nVoltageKey => $VoltageValue) {
                $VoltageSelected = '';
                if((isset($PortObj->PortVoltage)) and ($PortObj->PortVoltage == $nVoltageKey)) {$VoltageSelected = 'selected';}
                $VoltageContent .= "<option value='".$nVoltageKey."' ".$VoltageSelected.">".$VoltageValue."</option>";
            }
            $VoltageBody = "<select id='VoltageSelect_".$sUniqID."' class='VoltageSelectClass'>".$VoltageContent."</select>";

            ### VoltageGroup01
            $VoltageGroup01Body = "";
            $VoltageGroup01Content = "<option value='None'>---</option>";
            if((isset($PortObj->PortVoltage)) and ($PortObj->PortVoltage != array_keys($VoltageArray)[0]) and ($PortObj->PortVoltage != array_keys($VoltageArray)[1])) {
                foreach ($I2CDevices as $nDevKeyB => $DevB) {
                    foreach ($I2CArray['OLATS'] as $nOlatKeyB => $OlatB) {
                        $GPIO_SideB = ($nOlatKeyB == 0) ? "A" : "B";
                        for ($nPortB = 0; $nPortB < 8; $nPortB++) {
                            $sUniqIDB   = $DevB.'-'.$OlatB.'-'.$GPIO_SideB.'-'.$nPortB;
                            $PortObjB   = new GpioPort($sUniqIDB);
                            if((isset($PortObjB->PortPhase)) and ($PortObjB->PortPhase != array_keys($PhaseArray)[0]) and ($PortObjB->PortPhase != $PortObj->PortPhase)) {
                                $VoltageGroup01Selected = '';
                                if($PortObjB->PortId == $PortObj->SecondPortVoltage) {$VoltageGroup01Selected = 'selected';}
                                $PortHtmlName = explode('-', $PortObjB->PortId);
                                $PortHtmlName = $PortHtmlName[0].'-'.$PortHtmlName[2].'-'.$PortHtmlName[3];
                                $VoltageGroup01Content .= "<option value='".$PortObjB->PortId."' ".$VoltageGroup01Selected.">".$PortHtmlName."</option>";
                            }
                        }
                    }
                }
                $VoltageGroup01Body = "<select id='VoltageGroup01Select_".$sUniqID."' class='VoltageGroup01SelectClass'>".$VoltageGroup01Content."</select>";
            }

            ### VoltageGroup02
            $VoltageGroup02Body = "";
            $VoltageGroup02Content = "<option value='None'>---</option>";
            if((isset($PortObj->PortVoltage)) and ($PortObj->PortVoltage == array_keys($VoltageArray)[3])) {
                foreach ($I2CDevices as $nDevKeyB => $DevB) {
                    foreach ($I2CArray['OLATS'] as $nOlatKeyB => $OlatB) {
                        $GPIO_SideB = ($nOlatKeyB == 0) ? "A" : "B";
                        for ($nPortB = 0; $nPortB < 8; $nPortB++) {
                            $sUniqIDB   = $DevB.'-'.$OlatB.'-'.$GPIO_SideB.'-'.$nPortB;
                            $PortObjB   = new GpioPort($sUniqIDB);
                            if((isset($PortObjB->PortPhase)) and ($PortObjB->PortPhase != array_keys($PhaseArray)[0]) and ($PortObjB->PortPhase != $PortObj->PortPhase) and ($PortObjB->PortId != $PortObj->SecondPortVoltage)) {
                                $VoltageGroup01Selected = '';
                                if($PortObjB->PortId == $PortObj->ThirdPortVoltage) {$VoltageGroup01Selected = 'selected';}
                                $PortHtmlName = explode('-', $PortObjB->PortId);
                                $PortHtmlName = $PortHtmlName[0].'-'.$PortHtmlName[2].'-'.$PortHtmlName[3];
                                $VoltageGroup02Content .= "<option value='".$PortObjB->PortId."' ".$VoltageGroup01Selected.">".$PortHtmlName."</option>";
                            }
                        }
                    }
                }
                $VoltageGroup02Body = "<select id='VoltageGroup02Select_".$sUniqID."' class='VoltageGroup02SelectClass'>".$VoltageGroup02Content."</select>";
            }

            $GPIOStatus     = "<div id='GPIOStatusDivI2C_".$sUniqID."'      class='StatusDivClass'>  <img id='GPIOStatusImgI2C_".$sUniqID."'    src='' height='13px;'></div>";
            $RelayStatus    = "<div id='RelayStatusDivI2C_".$sUniqID."'     class='StatusDivClass'>  <img id='RelayStatusImgI2C_".$sUniqID."'   src='' height='13px;'></div>";
            $ButtonMode     = "<div id='ModeDivI2C_".$sUniqID."'            class='ModeDivClass'>    <img id='ModeImgI2C_".$sUniqID."'          src='' height='13px;'></div>";
            $ElectricPhase  = "<div id='PhaseDivI2C_".$sUniqID."'           class='PhaseDivClass'>   ".$PhaseBody."</div>";
            $UserPortName   = "<div id='PortNameI2C_".$PortId."'            class='PortNameDivClass'>".$UserPortNameBody."</div>";
            $Group          = "<div id='GroupDivI2C_".$sUniqID."'           class='GroupDivClass'>   ".$GroupBody."</div>";
            $Voltage        = "<div id='VoltageDivI2C_".$sUniqID."'         class='StatusDivClass'>  ".$VoltageBody."</div>";
            $VoltageGroup01 = "<div id='VoltageGroup01DivI2C_".$sUniqID."'  class='StatusDivClass'>  ".$VoltageGroup01Body."</div>";
            $VoltageGroup02 = "<div id='VoltageGroup02DivI2C_".$sUniqID."'  class='StatusDivClass'>  ".$VoltageGroup02Body."</div>";

            array_push($TableI2CArr, array($Dev,$GPIO_Side." (".$Olat.")",$nPort,$ButtonMode,$GPIOStatus,$RelayStatus,$ElectricPhase,$UserPortName,$Group,$Voltage,$VoltageGroup01,$VoltageGroup02));
            array_push($PortsI2CArr, array($Dev,$Olat,$GPIO_Side,$nPort,$sUniqID,intval($sRevBinReturn[$nPort])));
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Raspberry Pi Home Control System</title>
        <meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=yes" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/png" href="images/favicon.png" />
        <style type="text/css">@import url('resources/RaspberryGpio.css');</style>
        <link rel="stylesheet" href="resources/jquery-ui.css" />
        <link rel="stylesheet" href="resources/jquery.dataTables.css" />
        <script type="text/javascript" src="resources/jquery.min.js"></script>
        <script type="text/javascript" src="resources/jquery-ui.min.js"></script>
        <script type="text/javascript" src="resources/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="resources/RaspberryGpio.js"></script>
        <script type="text/javascript">
            var VoltageArray    = JSON.parse('<?php echo json_encode($VoltageArray);?>');
            var PhaseArray      = JSON.parse('<?php echo json_encode($PhaseArray);?>');
            var GroupsArray     = JSON.parse('<?php echo json_encode($GroupsArray);?>');
            var PortsGPIOArr    = <?php echo json_encode($PortsGPIOArr);?>;
            var TableGPIOArr    = <?php echo json_encode($TableGPIOArr);?>;
            var PortsI2CArr     = <?php echo json_encode($PortsI2CArr);?>;
            var TableI2CArr     = <?php echo json_encode($TableI2CArr);?>;
            jQuery(document).ready(OnLoad);
        </script>
    </head>
    <body>
        <div id="all_page">
            <?php
                if (count($I2CDevices)) {
            ?>
            <table id="TbI2C" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr><td colspan="10"><h1>Raspberry Pi Home Control System</h1></td><td colspan="2"><a href='index.php'><img id="MainImg" src="images/main.jpg">&nbsp;Main Panel</a></td></tr>
                    <tr><td>MCP23017 Physical Address</td><td>GPIO Side</td><td>Port</td><td>Port Mode</td><td>GPIO Port Status</td><td>Relay Status</td><td>Electric Phase</td><td>User Port Name</td><td>Group</td><td>Voltage</td><td>Voltage Group 1</td><td>Voltage Group 2</td></tr>
                </thead>
                <tbody></tbody>
            </table>
            <br /><br /><br />
            <?php
                }
            ?>
            <table id="TbPorts" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr><td colspan="5"><h1>Raspberry Pi Home Control System</h1></td><td><a href='index.php'><img id="MainImg" src="images/main.jpg">&nbsp;Main Panel</a></td></tr>
                    <tr><td>Physical Address</td><td>BCM Address</td><td>Port Name</td><td>Port Mode</td><td>Port Status</td><td>User Port Name</td></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </body>
</html>