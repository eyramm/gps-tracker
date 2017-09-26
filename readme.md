# Real-Time Vehicle Tracking Using Arduino

In this project, we can track vehicles and also turn the engine on or off, all in realtime using an Arduino. You can see the current location of the vehicle, the last seen location and the logs. This project is based on this [Cooking Hacks](https://www.cooking-hacks.com/projects/arduino-realtime-gps-gprs-vehicle-tracking) project.

# Features

The features of the various parts of this proect are highlighted below:

1.  #### Android  App

    1. Based on webview which displays the webapp

2.  #### Arduino Code

    1. Tracks vehicle location in realtime
    2. Posts the GPS location, time, date, course, altitude, speed, number of satellites used, etc, to a webserver

3. #### Web Server

    1. Stores location information received from Arduino into a text file 
    2. Shows the current of the vehicle. It refreshes every 30 seconds.
    3. Displays last seen location of the vehicle and ofther details like the location, time, and altitude. 


# Requirements

#### *Arduino:*  

  1. Arduino Uno
  2. [Elecro SIM808](https://www.aliexpress.com/item/High-Quality-SIM808-GPRS-GSM-GPS-Shield-2-in-1-Shield-GSM-GPRS-GPS-Development-Board/32515326895.html) (In software serial mode). 
  3. [240V/40A relay switch](https://www.aliexpress.com/item/Free-shipping-10Pcs-5V-30A-1-Channel-Relay-Module-Optocoupler-H-L-Level-Triger/32691375337.html)

#### *Web Server:*

  1.   Google Maps Javascript API
  2.   Host provider

#### *Software:*

  1. Arduino IDE
  2. Android Studio


# Set up

#### *Note:* Refer to the diagram for more information. 

  1. Fix the SIM808 shield into the Arduino Uno.
  2. Connect the relay switch to bridge to ignition switch or fuse. 
  2. Upload the code to the Arduino Uno using the IDE
  3. Upload the website code to your server and don't forget to add your Google Map Api code.
  4. Power up the Arduino and start tracking. This requires 12V/2A for stable operation. 


