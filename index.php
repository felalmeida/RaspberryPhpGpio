<?php
include_once("RaspberryGpio.php");
$I2CDevices     = GpioPort::GetI2CDevices();
$I2CStatus      = GpioPort::ReadI2CStatus();
$TbDevicesArr   = array();
$PortObjArr     = array();
foreach($I2CDevices as $nDevKey => $Dev) {
    foreach ($I2CArray['OLATS'] as $nOlatKey => $Olat) {
        $GPIO_Side      = ($nOlatKey == 0) ? "A" : "B";
        $nIntReturn     = intval($I2CStatus[$Dev."-".$Olat]);
        $sRevBinReturn  = strrev(GpioPort::IntToBin($nIntReturn));
        for ($nPort = 0; $nPort < 8; $nPort++) {
            $sUniqID    = $Dev.'-'.$Olat.'-'.$GPIO_Side.'-'.$nPort;
            $PortObj    = new GpioPort($sUniqID);
            if(((isset($PortObj->UserPortName)) and ($PortObj->UserPortName <> '')) or (intval($sRevBinReturn[$nPort]) === 0)){
                $PortInfo = $PortObj->GetAllInfo();
                $PortInfo['IntReturn']      = $nIntReturn;
                $PortInfo['RevBinReturn']   = $sRevBinReturn;
                $PortInfo['PortStatus']     = intval($sRevBinReturn[$nPort]);
                $PortInfo['RelayStatus']    = intval($sRevBinReturn[$nPort]) ? 0 : 1;
                $PortObjArr[$PortObj->PortId] = $PortInfo;
            }
        }
    }
}
foreach($PortObjArr as $nPortKey => $PortValue) {
    array_push($TbDevicesArr, array($PortValue['PortId'], $PortValue['UserPortName'], $GroupsArray[intval($PortValue['PortHomeGroup'])], $PortValue['RelayStatus']));
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
        <script type="text/javascript" src="resources/RaspberryGpioIndex.js"></script>
        <script type="text/javascript">
            var VoltageArray    = JSON.parse('<?php echo json_encode($VoltageArray);?>');
            var PhaseArray      = JSON.parse('<?php echo json_encode($PhaseArray);?>');
            var GroupsArray     = JSON.parse('<?php echo json_encode($GroupsArray);?>');
            var TbDevicesArr    = <?php echo json_encode($TbDevicesArr);?>;
            jQuery(document).ready(OnLoad);
        </script>
    </head>
    <body>
        <div id="all_page">
            <table id="TbDevices" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr><td colspan="3"><h1>Raspberry Pi Home Control System</h1></td><td><a href='config.php'><img id="GearImg" src="images/gear.png">&nbsp;Config Pannel</a></td></tr>
                    <tr><td>Physical Address</td><td>BCM Address</td><td>Port Name</td><td>Port Mode</td></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </body>
</html>