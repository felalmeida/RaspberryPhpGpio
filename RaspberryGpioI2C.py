#!/usr/bin/python
# -*- coding: UTF-8 -*-
################################################################################
# Module: RaspberryGpioI2C.py   Autor: SOL Digital Consultoria                 #
# Start:  03-Mar-2015           Last Update: 03-Mar-2015        Version: 1.0   #
################################################################################
"""
This module is used for getting and setting I2C IOs in Raspberry Pi
"""
import sys
import argparse
import smbus

sErrLog = []
PiBus   = smbus.SMBus(1)
DEVICES = [0x20, 0x21, 0x22, 0x23, 0x24, 0x25, 0x26, 0x27]
IODIRS  = [0x00, 0x01]
GPIOS   = [0x12, 0x13]
OLATS   = [0x14, 0x15]

def ErrLog(v_LogMsg):
    global sErrLog

    sErrLog.append(v_LogMsg)

def WriteValues(v_ThisDEVICES, v_ThisIODIRS, v_ThisGPIOS, v_ThisOLATS, v_Value):
    global PiBus

    try:
        for DEVICE in v_ThisDEVICES:
            for IODIR in v_ThisIODIRS: # Set 0 for OutPut and 1 for InPut
                PiBus.write_byte_data(DEVICE, IODIR, 0)
            for OLAT in v_ThisOLATS:
                StrResponse = PiBus.write_byte_data(DEVICE, OLAT, v_Value)
    except:
        ErrLog("=== ERROR === No such device: '" + hex(DEVICE) + "'")

def ReadValues(v_ThisDEVICES, v_ThisIODIRS, v_ThisGPIOS, v_ThisOLATS):
    global PiBus

    ArrResponse = []
    try:
        for DEVICE in v_ThisDEVICES:
            for OLAT in v_ThisOLATS:
                ArrResponse.append([
                    hex(DEVICE),
                    hex(OLAT),
                    PiBus.read_byte_data(DEVICE, OLAT)
                ])
    except:
        ErrLog("=== ERROR === No such device: '" + hex(DEVICE) + "'")
    return ArrResponse

def main():
    global DEVICES, IODIRS, GPIOS, OLATS

    arg_parser = argparse.ArgumentParser()
    arg_parser.add_argument('-d', action="store", dest="InDev",  required=False)
    arg_parser.add_argument('-g', action="store", dest="InGPIO", required=False)
    arg_parser.add_argument('-v', action="store", dest="InVal",  required=False)
    args = arg_parser.parse_args()

    InDev   = str(args.InDev).strip()
    InDev   = '' if (InDev == 'None') else InDev.lower()
    InGPIO  = str(args.InGPIO).strip()
    InGPIO  = '' if (InGPIO == 'None') else InGPIO.lower()
    InVal   = str(args.InVal).strip()
    nValue  = 0 if (InVal == 'None') else int(InVal.lower())
    nValue  = 0 if (nValue < 0) else 255 if (nValue > 255) else nValue

    This_DEVICES = DEVICES
    This_IODIRS  = IODIRS
    This_GPIOS   = GPIOS
    This_OLATS   = OLATS

    if (InGPIO == 'a'):
        This_IODIRS  = [IODIRS[0]]
        This_GPIOS   = [GPIOS[0]]
        This_OLATS   = [OLATS[0]]
    elif (InGPIO == 'b'):
        This_IODIRS  = [IODIRS[1]]
        This_GPIOS   = [GPIOS[1]]
        This_OLATS   = [OLATS[1]]

    if (InDev.startswith("0x") and (int(InDev, 16) in DEVICES)):
        This_DEVICES = [int(InDev, 16)]

    if (InVal != 'None'):
        WriteValues(This_DEVICES, This_IODIRS, This_GPIOS, This_OLATS, nValue)

    print ReadValues(This_DEVICES, This_IODIRS, This_GPIOS, This_OLATS)

main()
