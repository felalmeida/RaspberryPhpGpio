# RaspberryPhpGpio
PHP interface to use RaspberryPi GPIO

You will need to install 'wiringPi', to do so use these commands:
1) cd ~
2) git clone git://git.drogon.net/wiringPi
3) cd wiringPi/
4) ./build

That's it, now you will need the path to 'gpio':
1) which gpio

The response of this command you update the first line of 'RaspberryGpio.php':
$GPIO = "/usr/local/bin/gpio ";

That's all.

Now you should install apache or lighttpd, in both cases you need php working.

You should put all these files to your web root, in most cases it is '/var/www/'

###
The board is available (OshPark) @ https://oshpark.com/shared_projects/nkAzt4QZ

The board is available (PcbWay)  @ https://www.pcbway.com/project/shareproject/RaspberryPi_GPIO_Extender_v2_1.html