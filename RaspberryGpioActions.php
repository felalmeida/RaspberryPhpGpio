<?php
include_once('RaspberryGpio.php');

$PortId                 = isset($_GET['PortId'])                ? trim($_GET['PortId'])             : -1;
$GetPortInfo            = isset($_GET['GetPortInfo'])           ? True                              : False;
$SwitchStatus           = isset($_GET['SwitchStatus'])          ? True                              : False;
$SwitchMode             = isset($_GET['SwitchMode'])            ? True                              : False;
$ModifyUserPortName     = isset($_GET['ModifyUserPortName'])    ? True                              : False;
$NewName                = isset($_GET['NewName'])               ? trim($_GET['NewName'])            : '';
$ModifyPhase            = isset($_GET['ModifyPhase'])           ? True                              : False;
$NewPhase               = isset($_GET['NewPhase'])              ? trim($_GET['NewPhase'])           : '';
$ModifyGroup            = isset($_GET['ModifyGroup'])           ? True                              : False;
$NewGroup               = isset($_GET['NewGroup'])              ? trim($_GET['NewGroup'])           : '';
$ModifyVoltage          = isset($_GET['ModifyVoltage'])         ? True                              : False;
$NewVoltage             = isset($_GET['NewVoltage'])            ? trim($_GET['NewVoltage'])         : '';
$ModifyVoltageGroup01   = isset($_GET['ModifyVoltageGroup01'])  ? True                              : False;
$NewVoltageGroup01      = isset($_GET['NewVoltageGroup01'])     ? trim($_GET['NewVoltageGroup01'])  : '';
$ModifyVoltageGroup02   = isset($_GET['ModifyVoltageGroup02'])  ? True                              : False;
$NewVoltageGroup02      = isset($_GET['NewVoltageGroup02'])     ? trim($_GET['NewVoltageGroup02'])  : '';
$GetPortInfoI2C         = isset($_GET['GetPortInfoI2C'])        ? True                              : False;
$SwitchStatusI2C        = isset($_GET['SwitchStatusI2C'])       ? True                              : False;
$SwitchModeI2C          = isset($_GET['SwitchModeI2C'])         ? True                              : False;

if($PortId === -1) {return json_encode(array('Return' => 'Error. Invalid PortId'));}

$PortObj = new GpioPort($PortId);

if ($GetPortInfo)           {$Return = array('GetMode' => $PortObj->Mode, 'GetStatus' => $PortObj->Status, 'GetUserPortName' => $PortObj->UserPortName);}
if ($SwitchStatus)          {$Return = $PortObj->InvertStatus();}
if ($SwitchMode)            {$Return = $PortObj->InvertMode();}
if ($ModifyUserPortName)    {$Return = $PortObj->SetUserPortName($NewName);}
if ($ModifyPhase)           {$Return = $PortObj->SetPortPhase($NewPhase);}
if ($ModifyGroup)           {$Return = $PortObj->SetPortGroup($NewGroup);}
if ($ModifyVoltage)         {$Return = $PortObj->SetVoltage($NewVoltage);}
if ($ModifyVoltageGroup01)  {$Return = $PortObj->SetVoltageGroup01($NewVoltageGroup01);}
if ($ModifyVoltageGroup02)  {$Return = $PortObj->SetVoltageGroup02($NewVoltageGroup02);}

if ($GetPortInfoI2C) {
    $sVars = explode('-', trim($PortId));
    $nIntReturn = intval(GpioPort::ReadI2CStatus($sVars[0], $sVars[1])[$sVars[0].'-'.$sVars[1]]);
    $sRevBinReturn = strrev(GpioPort::IntToBin($nIntReturn));

    $Return = intval($sRevBinReturn[$sVars[3]]);
}

if ($SwitchStatusI2C) {
    $sVars = explode('-', trim($PortId));
    $nIntReturn = intval(GpioPort::ReadI2CStatus($sVars[0], $sVars[1])[$sVars[0].'-'.$sVars[1]]);
    $sRevBinReturn = strrev(GpioPort::IntToBin($nIntReturn));

    $sNewBinValue = $sRevBinReturn;
    if (intval($sRevBinReturn[$sVars[3]]) == 1) {
        $sNewBinValue[$sVars[3]] = '0';
    } else {
        $sNewBinValue[$sVars[3]] = '1';
    }

    $nIntReturn = intval($PortObj->SetI2CStatus($sVars[0], $sVars[1], bindec(strrev($sNewBinValue)))[$sVars[0].'-'.$sVars[1]]);
    $sRevBinReturn = strrev(GpioPort::IntToBin($nIntReturn));

    $Return = intval($sRevBinReturn[$sVars[3]]);
}

echo json_encode(array('Return' => $Return));
?>