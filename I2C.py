#!/usr/bin/python
# -*- coding: UTF-8 -*-
################################################################################
# Module: I2C.py                Autor: SOL Digital Consultoria                 #
# Start:  03-Mar-2015           Last Update: 03-Mar-2015        Version: 1.0   #
################################################################################
"""
This module is used for getting and setting I2C IOs in Raspberry Pi
"""

#http://hertaville.com/interfacing-an-i2c-gpio-expander-mcp23017-to-the-raspberry-pi-using-c.html
#http://robotic-controls.com/learn/electronics/io-port-expander-mcp23017-and-mcp23008
import RPi.GPIO as GPIO
import smbus
import time

bus = smbus.SMBus(1)

'''
DEVICE_01   = 0x20
DEVICE_02   = 0x21
DEVICE_03   = 0x22
DEVICE_04   = 0x23
DEVICE_05   = 0x24
DEVICE_06   = 0x25
DEVICE_07   = 0x26
DEVICE_08   = 0x27
IODIR_A     = 0x00
IODIR_B     = 0x01
GPIO_A      = 0x12
GPIO_B      = 0x13
OLAT_A      = 0x14
OLAT_B      = 0x15
'''

#DEVICES = [0x20, 0x21, 0x22, 0x23, 0x24, 0x25, 0x26, 0x27]
DEVICES = [0x20]
IODIRS  = [0x00, 0x01]
GPIOS   = [0x12, 0x13]
OLATS   = [0x14, 0x15]
tSleep  = (0.005 * 100)

def InitAll():
    global DEVICES, IODIRS, OLATS

    for DEVICE in DEVICES:
        for IODIR in IODIRS: # Set 0 for OutPut and 1 for InPut
            bus.write_byte_data(DEVICE,IODIR, 0)
        for OLAT in OLATS: # 255 for all Off (Relay Status)
            bus.write_byte_data(DEVICE,OLAT, 255)

try:
    InitAll()

    while True:
        for DEVICE in DEVICES:
            for OLAT in OLATS:
                #for MyData in range(0,256):
                #for MyData in [255,0,1,3,7,15,31,63,127,255]:
                #for MyData in [255,127,63,31,15,7,3,1,0,255]:
                #for MyData in [255,0,1,2,4,8,16,32,64,128,255]:
                #for MyData in [255,128,64,32,16,8,4,1,0,255]:
                for MyData in [254,253,251,247,239,223,191,127,255]:
                    bus.write_byte_data(DEVICE,OLAT,MyData)
                    response = bus.read_byte_data(DEVICE,OLAT)

                    '''
                    print ("DEV: {0:02x}".format(DEVICE) + 
                          "(OLAT: {0:02x}".format(OLAT) + ") -> " +
                          "VAL: {0:03d}".format(response) + " -> " +
                          "BinVAL: {0:08b}".format(response))
                    '''

                    time.sleep(tSleep)
                    #raw_input('Next...')
except KeyboardInterrupt:
    InitAll()
    GPIO.cleanup()
