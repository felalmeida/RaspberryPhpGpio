<?php
/*
1)
Enable I2C and SPI in raspi-config
chmod 666 /dev/i2c-1 (Make Writable/Readable by everybody) - EVERY BOOT
### Edit the /etc/rc.local and put this command there (Before 'exit')

2)
aptitude install python-dev python-smbus i2c-tools libi2c-dev python-webpy lighttpd

3)
http://wiringpi.com/download-and-install/

4)
https://pypi.python.org/pypi/RPi.GPIO (Download Latest Version)
tar zxf RPi.GPIO-x.x.x.tar.gz
cd RPi.GPIO-x.x.x
python setup.py install
*/

$PortsArray = array (
    array('PhysicalId' => 27, 'PortId' => 0,  'PortName' => 'ID_SD (I2C ID EEPROM)', 'PortDefaultDesc' => ''),
    array('PhysicalId' => 28, 'PortId' => 1,  'PortName' => 'ID_SC (I2C ID EEPROM)', 'PortDefaultDesc' => ''),
    array('PhysicalId' => 3,  'PortId' => 2,  'PortName' => 'GPIO02 (SDA1, I2C)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 5,  'PortId' => 3,  'PortName' => 'GPIO03 (SCL1, I2C)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 7,  'PortId' => 4,  'PortName' => 'GPIO04 (GPIO_GCLK)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 29, 'PortId' => 5,  'PortName' => 'GPIO05',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 31, 'PortId' => 6,  'PortName' => 'GPIO06',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 26, 'PortId' => 7,  'PortName' => 'GPIO07 (SPI_CE1_N)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 24, 'PortId' => 8,  'PortName' => 'GPIO08 (SPI_CE0_N)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 21, 'PortId' => 9,  'PortName' => 'GPIO09 (SPI_MISO)',     'PortDefaultDesc' => ''),
    array('PhysicalId' => 19, 'PortId' => 10, 'PortName' => 'GPIO10 (SPI_MOSI)',     'PortDefaultDesc' => ''),
    array('PhysicalId' => 23, 'PortId' => 11, 'PortName' => 'GPIO11 (SPI_CLK)',      'PortDefaultDesc' => ''),
    array('PhysicalId' => 32, 'PortId' => 12, 'PortName' => 'GPIO12',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 33, 'PortId' => 13, 'PortName' => 'GPIO13',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 8,  'PortId' => 14, 'PortName' => 'GPIO14 (TDX0)',         'PortDefaultDesc' => ''),
    array('PhysicalId' => 10, 'PortId' => 15, 'PortName' => 'GPIO15 (RXD0)',         'PortDefaultDesc' => ''),
    array('PhysicalId' => 36, 'PortId' => 16, 'PortName' => 'GPIO16',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 11, 'PortId' => 17, 'PortName' => 'GPIO17 (GPIO_GEN0)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 12, 'PortId' => 18, 'PortName' => 'GPIO18 (GPIO_GEN1)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 35, 'PortId' => 19, 'PortName' => 'GPIO19',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 38, 'PortId' => 20, 'PortName' => 'GPIO20',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 40, 'PortId' => 21, 'PortName' => 'GPIO21',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 15, 'PortId' => 22, 'PortName' => 'GPIO22 (GPIO_GEN3)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 16, 'PortId' => 23, 'PortName' => 'GPIO23 (GPIO_GEN4)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 18, 'PortId' => 24, 'PortName' => 'GPIO24 (GPIO_GEN5)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 22, 'PortId' => 25, 'PortName' => 'GPIO25 (GPIO_GEN6)',    'PortDefaultDesc' => ''),
    array('PhysicalId' => 37, 'PortId' => 26, 'PortName' => 'GPIO26',                'PortDefaultDesc' => ''),
    array('PhysicalId' => 13, 'PortId' => 27, 'PortName' => 'GPIO27 (GPIO_GEN2)',    'PortDefaultDesc' => '')
);

$I2CArray = array (
    'DEVICES' => array('0x20', '0x21', '0x22', '0x23', '0x24', '0x25', '0x26', '0x27'),
    'IODIRS'  => array('0x00', '0x01'),
    'GPIOS'   => array('0x12', '0x13'),
    'OLATS'   => array('0x14', '0x15')
);

$PhaseArray = array (
    0   => '---',
    1   => 'Phase L1',
    2   => 'Phase L2',
    3   => 'Phase L3'
);

$GroupsArray = array (
    0   => '---',
    1   => 'Sala',
    2   => 'Cozinha',
    3   => 'Varanda',
    4   => 'Externo',
    5   => 'Quarto 01',
    6   => 'Quarto 02',
    7   => 'Quarto 03'
);

$VoltageArray = array (
    0   => '---',
    1   => '110v',
    2   => '220v',
    3   => '380v'
);

$ArrayChars01 = array('"',      '\'',       '`');
$ArrayChars02 = array('&quot;', '&rsquo;',  '&apos;');

$GPIO       = '/usr/local/bin/gpio ';
$I2C        = '/usr/bin/python '.getcwd().'/'.'RaspberryGpioI2C.py ';
$I2C_Detect = '/usr/sbin/i2cdetect ';

################################################################################
class GpioPort {
    private $ExtraInfoFile;
    private $ExtraInfoSeparator;

    private $FilePortId;

    public $IsDefined;
    public $IsGPIO;

    public $PortId;
    public $UserPortName;
    public $PortPhase;
    public $PortVoltage;
    public $SecondPortVoltage;
    public $ThirdPortVoltage;
    public $PortHomeGroup;
    public $Status;
    public $Mode;

    public function __construct($v_PortId = '') {
        ### Var Initialization
        $this->ExtraInfoFile = substr(__FILE__, 0, strlen(__FILE__)-4).'_Extra.lst';
        $this->ExtraInfoSeparator = '|';

        if (!file_exists($this->ExtraInfoFile)) {
            $FileObj = fopen($this->ExtraInfoFile, 'w') or die('Unable to open file!');
            fclose($FileObj);
        }

        if(strlen(trim(strval($v_PortId))) == 0) {
            $this->PortId = 'NotDefined';
            $this->FilePortId = $this->PortId;
            $this->IsDefined = False;
        } else {
            $this->IsDefined = True;
            $this->PortId = trim(strval($v_PortId));
            if (is_numeric($v_PortId)) { // GPIO Ports
                $this->FilePortId = 'GPIO_'.$this->PortId;
                $this->IsGPIO = True;
            } else {                     // I2C Ports
                $this->FilePortId = $this->PortId;
                $this->IsGPIO = False;
            }
        }

        if($this->IsDefined) {
            $this->GetExtraInfo($this->PortId);
            if($this->IsGPIO) {
                $this->GetStatus($this->PortId);
                $this->GetMode($this->PortId);
            }
        }
    }

    public function GetAllInfo($v_IsJson = False) {
        $LocalArray = array(
            'IsGPIO'            => $this->IsGPIO ? 'Yes' : 'No',
            'PortId'            => $this->PortId,
            'UserPortName'      => $this->UserPortName,
            'PortPhase'         => $this->PortPhase,
            'PortVoltage'       => $this->PortVoltage,
            'SecondPortVoltage' => $this->SecondPortVoltage,
            'ThirdPortVoltage'  => $this->ThirdPortVoltage,
            'PortHomeGroup'     => $this->PortHomeGroup,
            'Status'            => $this->Status,
            'Mode'              => $this->Mode
        );

        if(($v_IsJson === True) or (intval($v_IsJson) === 1)) {
            return json_encode($LocalArray);
        } else {
            return $LocalArray;
        }
    }

    private function GetExtraInfo($v_PortId = '') {
        global $ArrayChars01, $ArrayChars02;
        # ExtraInfo File Specs
        # PORT_ID | USER_PORT_NAME | PORT_PHASE | PORT_VOLTAGE | SECOND_PORT_VOLTAGE | THIRD_PORT_VOLTAGE | PORT_HOME_GROUP

        $LocalFilePortId = $this->FilePortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalFilePortId = 'GPIO_'.$v_PortId;
            } else {                     // I2C Ports
                $LocalFilePortId = $v_PortId;
            }
        }

        $FileObj = fopen($this->ExtraInfoFile, 'r') or die('Unable to open file!');
        while($Line = fgets($FileObj)) {
            $Line = str_replace($ArrayChars01, $ArrayChars02, $Line);
            $LineArgs = explode($this->ExtraInfoSeparator, $Line);
            if(!(strpos($LineArgs[0], $LocalFilePortId) === false)) {
                switch(count($LineArgs)) {
                    case 7:
                        $this->UserPortName         = trim($LineArgs[1]);
                        $this->PortPhase            = trim($LineArgs[2]);
                        $this->PortVoltage          = trim($LineArgs[3]);
                        $this->SecondPortVoltage    = trim($LineArgs[4]);
                        $this->ThirdPortVoltage     = trim($LineArgs[5]);
                        $this->PortHomeGroup        = trim($LineArgs[6]);
                        break;
                    case 6:
                        $this->UserPortName         = trim($LineArgs[1]);
                        $this->PortPhase            = trim($LineArgs[2]);
                        $this->PortVoltage          = trim($LineArgs[3]);
                        $this->SecondPortVoltage    = trim($LineArgs[4]);
                        $this->ThirdPortVoltage     = trim($LineArgs[5]);
                        unset($this->PortHomeGroup);
                        break;
                    case 5:
                        $this->UserPortName         = trim($LineArgs[1]);
                        $this->PortPhase            = trim($LineArgs[2]);
                        $this->PortVoltage          = trim($LineArgs[3]);
                        $this->SecondPortVoltage    = trim($LineArgs[4]);
                        unset($this->ThirdPortVoltage);
                        unset($this->PortHomeGroup);
                        break;
                    case 4:
                        $this->UserPortName         = trim($LineArgs[1]);
                        $this->PortPhase            = trim($LineArgs[2]);
                        $this->PortVoltage          = trim($LineArgs[3]);
                        unset($this->SecondPortVoltage);
                        unset($this->ThirdPortVoltage);
                        unset($this->PortHomeGroup);
                        break;
                    case 3:
                        $this->UserPortName         = trim($LineArgs[1]);
                        $this->PortPhase            = trim($LineArgs[2]);
                        unset($this->PortVoltage);
                        unset($this->SecondPortVoltage);
                        unset($this->ThirdPortVoltage);
                        unset($this->PortHomeGroup);
                        break;
                    case 2:
                        $this->UserPortName         = trim($LineArgs[1]);
                        unset($this->PortPhase);
                        unset($this->PortVoltage);
                        unset($this->SecondPortVoltage);
                        unset($this->ThirdPortVoltage);
                        unset($this->PortHomeGroup);
                        break;
                }
                break;
            }
        }
        fclose($FileObj);
        if (strlen(trim($this->UserPortName))       == 0) {unset($this->UserPortName);}
        if (strlen(trim($this->PortPhase))          == 0) {unset($this->PortPhase);}
        if (strlen(trim($this->PortVoltage))        == 0) {unset($this->PortVoltage);}
        if (strlen(trim($this->SecondPortVoltage))  == 0) {unset($this->SecondPortVoltage);}
        if (strlen(trim($this->ThirdPortVoltage))   == 0) {unset($this->ThirdPortVoltage);}
        if (strlen(trim($this->PortHomeGroup))      == 0) {unset($this->PortHomeGroup);}
    }

    private function SetExtraInfo($v_PortId = '') {
        global $ArrayChars01, $ArrayChars02;

        # ExtraInfo File Specs
        # PORT_ID | USER_PORT_NAME | PORT_PHASE | PORT_VOLTAGE | SECOND_PORT_VOLTAGE | THIRD_PORT_VOLTAGE | PORT_HOME_GROUP

        $LocalFilePortId = $this->FilePortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalFilePortId = 'GPIO_'.$v_PortId;
            } else {                     // I2C Ports
                $LocalFilePortId = $v_PortId;
            }
        }

        $ExtraInfoLine  = $LocalFilePortId.$this->ExtraInfoSeparator;
        $ExtraInfoLine .= (isset($this->UserPortName)       ? $this->UserPortName       : '').$this->ExtraInfoSeparator;
        $ExtraInfoLine .= (isset($this->PortPhase)          ? $this->PortPhase          : '').$this->ExtraInfoSeparator;
        $ExtraInfoLine .= (isset($this->PortVoltage)        ? $this->PortVoltage        : '').$this->ExtraInfoSeparator;
        $ExtraInfoLine .= (isset($this->SecondPortVoltage)  ? $this->SecondPortVoltage  : '').$this->ExtraInfoSeparator;
        $ExtraInfoLine .= (isset($this->ThirdPortVoltage)   ? $this->ThirdPortVoltage   : '').$this->ExtraInfoSeparator;
        $ExtraInfoLine .= (isset($this->PortHomeGroup)      ? $this->PortHomeGroup      : '');

        $OutPut = array();
        $FileObj = fopen($this->ExtraInfoFile, 'r') or die('Unable to open file!');
        while($Line = fgets($FileObj)) {
            array_push($OutPut, trim($Line));
        }
        fclose($FileObj);

        $bFouded = False;
        foreach($OutPut as $KeyLine => $DataLine) {
            $LineArgs = explode($this->ExtraInfoSeparator, $DataLine);
            if(!(strpos($LineArgs[0], $LocalFilePortId) === false)) {
                $OutPut[$KeyLine] = $ExtraInfoLine;
                $bFouded = True;
                break;
            }
        }

        if(!$bFouded) {
            array_push($OutPut, $ExtraInfoLine);
        }

        asort($OutPut);
        $FileObj = fopen($this->ExtraInfoFile, 'w') or die('Unable to open file!');
        foreach($OutPut as $DataLine){
            $Line = str_replace($ArrayChars02, $ArrayChars01, $DataLine);
            fwrite($FileObj, $Line."\n");
        }
        fclose($FileObj);

        $this->GetExtraInfo($v_PortId);
    }

    public function SetUserPortName($v_sNewName = '') {
        $this->UserPortName = trim($v_sNewName);
        $this->SetExtraInfo();
        return (isset($this->UserPortName) ? $this->UserPortName : '');
    }

    public function SetPortPhase($v_sNewPhase = 'None') {
        $this->PortPhase = trim($v_sNewPhase);
        $this->SetExtraInfo();
        return (isset($this->PortPhase) ? $this->PortPhase : '');
    }

    public function SetPortGroup($v_sNewGroup = 'None') {
        $this->PortHomeGroup = trim($v_sNewGroup);
        $this->SetExtraInfo();
        return (isset($this->PortHomeGroup) ? $this->PortHomeGroup : '');
    }

    public function SetVoltage($v_sNewVoltage = 'None') {
        $this->PortVoltage = trim($v_sNewVoltage);
        $this->SetExtraInfo();
        return (isset($this->PortVoltage) ? $this->PortVoltage : '');
    }

    public function SetVoltageGroup01($v_sNewVoltageGroup01 = 'None') {
        $this->SecondPortVoltage = trim($v_sNewVoltageGroup01);
        $this->SetExtraInfo();
        return (isset($this->SecondPortVoltage) ? $this->SecondPortVoltage : '');
    }

    public function SetVoltageGroup02($v_sNewVoltageGroup02 = 'None') {
        $this->ThirdPortVoltage = trim($v_sNewVoltageGroup02);
        $this->SetExtraInfo();
        return (isset($this->ThirdPortVoltage) ? $this->ThirdPortVoltage : '');
    }

    ### GPIO
    private function SetMode($v_PortId = '', $v_Mode = 'OUT') {
        global $GPIO;

        $LocalPortId = $this->PortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalPortId = trim($v_PortId);
            }
        }

        switch ($v_Mode) {
            case 'IN':
                shell_exec($GPIO.'-g mode '.$LocalPortId.' input');
                $this->SetStatus($LocalPortId, 0);
                break;
            case 'OUT':
                shell_exec($GPIO.'-g mode '.$LocalPortId.' output');
                break;
            default:
                shell_exec($GPIO.'-g mode '.$LocalPortId.' output');
                break;
        }
    }

    private function GetMode($v_PortId = '') {
        global $GPIO;

        $LocalPortId = $this->PortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalPortId = trim($v_PortId);
            }
        }

        foreach(explode("\n", shell_exec($GPIO."readall | awk -F\"|\" '{ print $2,$5,\"#\",$14,$11 }' | sed 's/ //g' | sed 's/|//g' | grep '^[0-9]\|^#' | grep ".$LocalPortId."[\"I|O|A\"]")) as $nLineKey => $sLine) {
            foreach(explode('#', $sLine) as $nModeKey => $sMode) {
                if (trim($sMode) <> '') {
                    preg_match_all('!\d+!', $sMode, $nExtractedPort);
                    if (intval($nExtractedPort[0][0]) === intval($LocalPortId)) {
                        $this->Mode = trim(strtoupper(str_replace(range(0,9),'',$sMode)));
                    }
                }
            }
        }
    }

    public function InvertMode($v_PortId = '') {
        $LocalPortId = $this->PortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalPortId = trim($v_PortId);
            }
        }

        if ($this->Mode == 'OUT') {
            $this->SetMode($LocalPortId, 'IN');
        } else {
            $this->SetMode($LocalPortId, 'OUT');
        }

        $this->GetMode($LocalPortId);

        return $this->Mode;
    }

    private function SetStatus($v_PortId, $v_nStatusVal) {
        global $GPIO;

        $LocalPortId = $this->PortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalPortId = trim($v_PortId);
            }
        }

        return intval(trim(shell_exec($GPIO.'-g write '.$LocalPortId.' '.$v_nStatusVal)));
    }

    private function GetStatus($v_PortId = '') {
        global $GPIO;

        $LocalPortId = $this->PortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalPortId = trim($v_PortId);
            }
        }

        $this->Status = intval(trim(shell_exec($GPIO.'-g read '.$LocalPortId)));
    }

    public function InvertStatus($v_PortId = '') {
        $LocalPortId = $this->PortId;
        if(strlen(trim(strval($v_PortId))) != 0) {
            if (is_numeric($v_PortId)) { // GPIO Ports
                $LocalPortId = trim($v_PortId);
            }
        }

        if ($this->Status === 1) {
            $this->SetStatus($LocalPortId, 0);
        } else {
            $this->SetStatus($LocalPortId, 1);
        }

        $this->GetStatus($LocalPortId);

        return $this->Status;
    }

    ### I2C - Non Object Dependent Functions
    public function GetI2CDevices() {
        global $I2C_Detect;

        $I2CDevices = array();
        foreach (explode("\n", shell_exec($I2C_Detect."-y 1 | grep -v ^\" \"")) as $nLineKey => $sLine) {
            foreach (explode(' ', $sLine) as $nColumnKey => $sColumn) {
                if (trim($sColumn) <> '--' and trim($sColumn) <> '' and !strpos(trim($sColumn), ':')) {
                    array_push($I2CDevices, strtolower(trim('0x'.$sColumn)));
                }
            }
        }
        return $I2CDevices;
    }

    public function ReadI2CStatus($v_sI2CDevice = 'ALL', $v_sI2CDevGPIO = 'ALL') {
        global $I2CArray, $I2C;

        $sCMD = $I2C;
        $ArrReturn = array();
        if ($v_sI2CDevice != 'ALL') {
            if (in_array(strtolower(trim($v_sI2CDevice)), $I2CArray['DEVICES'])) {
                $sCMD = $sCMD.' -d '.strtolower(trim($v_sI2CDevice));
            }
        }

        if ($v_sI2CDevGPIO != 'ALL') {
            if (strtolower(trim($v_sI2CDevGPIO)) == $I2CArray['OLATS'][0]) {
                $sCMD = $sCMD.' -g '.'a';
            } else if (strtolower(trim($v_sI2CDevGPIO)) == $I2CArray['OLATS'][1]) {
                $sCMD = $sCMD.' -g '.'b';
            }
        }

        foreach (explode("\n", shell_exec($sCMD)) as $nLineKey => $sLine) {
            if(trim($sLine) <> '') {
                $sLine = str_replace(array("'", "[", "]]", " "), "", $sLine);
                foreach(explode(']', $sLine) as $nResponseKey => $ResponseVal) {
                    $ResponseVal = explode(',', ltrim(trim($ResponseVal), ','));
                    $ArrReturn[$ResponseVal[0].'-'.$ResponseVal[1]] = $ResponseVal[2];
                }
            }
        }

        return $ArrReturn;
    }

    public function SetI2CStatus($v_sI2CDevice = 'All', $v_sI2CDevGPIO = 'All', $v_nI2CValue = 0) {
        global $I2CArray, $I2C;

        $sCMD = $I2C;
        if ($v_sI2CDevice != 'ALL') {
            if (in_array(strtolower(trim($v_sI2CDevice)), $I2CArray['DEVICES'])) {
                $sCMD = $sCMD.' -d '.strtolower(trim($v_sI2CDevice));
            }
        }

        if ($v_sI2CDevGPIO != 'ALL') {
            if (strtolower(trim($v_sI2CDevGPIO)) == $I2CArray['OLATS'][0]) {
                $sCMD = $sCMD.' -g '.'a';
            } else if (strtolower(trim($v_sI2CDevGPIO)) == $I2CArray['OLATS'][1]) {
                $sCMD = $sCMD.' -g '.'b';
            }
        }

        $sCMD = $sCMD.' -v '.$v_nI2CValue;
        shell_exec($sCMD);

        return GpioPort::ReadI2CStatus($v_sI2CDevice, $v_sI2CDevGPIO);
    }

    public function IntToBin($v_IntVal = 0) {
        return str_pad(sprintf('%b', $v_IntVal), 8, '0', STR_PAD_LEFT);
    }
}
################################################################################
?>
