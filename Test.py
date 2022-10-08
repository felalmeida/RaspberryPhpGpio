#!/usr/bin/python
# -*- coding: UTF-8 -*-
################################################################################
# Module: Test.py               Autor: SOL Digital Consultoria                 #
# Start:  03-Mar-2015           Last Update: 03-Mar-2015        Version: 1.0   #
################################################################################
"""
This module is used for testing I2C IOs in Raspberry Pi
"""

import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)
pinList = [21]
tSleep  = 0.01

for nPin in pinList:
    GPIO.setup(nPin, GPIO.OUT)
    GPIO.output(nPin, GPIO.HIGH)

def main():
    while True:
        for nPin in pinList:
            #print "Set "+str(nPin)+" To LOW"
            GPIO.output(nPin, GPIO.LOW)
            time.sleep(tSleep)
            #print "Set "+str(nPin)+" To HIGH"
            GPIO.output(nPin, GPIO.HIGH)
            time.sleep(tSleep)
    GPIO.cleanup()

try:
    main()
except KeyboardInterrupt:
    GPIO.cleanup()

