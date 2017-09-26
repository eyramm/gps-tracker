#include <SoftwareSerial.h>
#include <String.h>

SoftwareSerial mySerial(7, 8);

int8_t answer;
int Powerkey = 9;

char data[100];
int data_size;

char frame[200];

byte GNSSrunstatus;;
byte Fixstatus;
char UTCdatetime[18];
char latitude[10];
char longitude[11];
char time[18];
char altitude[8];
char speedOTG[6];
char course[6];
byte fixmode;
char HDOP[4];
char PDOP[4];
char VDOP[4];
char satellitesinview[2];
char satellites[2];
char GLONASSsatellitesused[2];
char cn0max[2];
char HPA[6];
char VPA[6];


int led1 = 10;
byte led = 10;
byte led_on = 0;
byte led_off = 0;
byte sms_control = 0;
long last_millis = 0;
const int delay_time = 5000;
const int buff_size = 10;
char buffer[buff_size];


void setup() {
	Serial.begin(9600);
	mySerial.begin(9600);


	pinMode(led, OUTPUT);
	digitalWrite(led1, HIGH);

	pinMode(Powerkey, OUTPUT);
	power_on();
	delay(5000);
	start_GPS();
	network_connect();


}


void loop() {

	get_GPS();
	sendHTTP();
	sms();
}



void power_on() {
	digitalWrite(Powerkey, HIGH);
	Serial.println("Powering On");
	delay(10000);
	Serial.println("Powered On");
}


void network_connect() {

	mySerial.println("AT+CPIN?");
	delay(1000);
	ShowSerialData();



	mySerial.println("AT+COPS?");
	delay(1000);
	ShowSerialData();


	mySerial.println("AT+CREG?");
	delay(1000);
	ShowSerialData();

	mySerial.println("AT+SAPBR=3,1,\"CONTYPE\",\"GPRS\"");
	delay(1000);
	ShowSerialData();

	mySerial.print("AT+CMGF=1\r");
	delay(1000);
	ShowSerialData();

	mySerial.println("AT+CMGD=1,4");
	delay(1000);
	ShowSerialData();

	mySerial.println("AT+SAPBR=3,1,\"APN\",\"internet\"");
	delay(1000);
	ShowSerialData();

	mySerial.println("AT+SAPBR=3,1,\"USER\",\"\"");
	delay(1000);
	ShowSerialData();

	mySerial.println("AT+SAPBR=3,1,\"PWD\",\"\"");
	delay(1000);
	ShowSerialData();

	mySerial.println("AT+SAPBR=1,1");
	delay(5000);
	ShowSerialData();

	mySerial.println("AT+HTTPINIT");
	delay(1000);
	ShowSerialData();

}


void ShowSerialData()
{
	while (mySerial.available() != 0)
		Serial.write(mySerial.read());
}


int8_t start_GPS() {

	mySerial.println("AT+CGPSPWR=1");
	delay(100);
	ShowSerialData();

	mySerial.println("AT+CGPSRST=1");
	delay(100);
	ShowSerialData();

	Serial.println("Turning on GPS");
	delay(15000);

	mySerial.println("AT+CGPSSTATUS?");
	delay(100);
	ShowSerialData();

}


/* strtok_fixed - fixed variation of strtok_single */
static char *strtok_single(char *str, char const *delims)
{
	static char  *src = NULL;
	char  *p,  *ret = 0;

	if (str != NULL)
		src = str;

  if (src == NULL || *src == '\0')    // Fix 1
  	return NULL;

  ret = src;                          // Fix 2
  if ((p = strpbrk(src, delims)) != NULL)
  {
  	*p  = 0;
  	src = ++p;
  }
  else
  	src += strlen(src);

  return ret;
}


void sendHTTP()
{
	mySerial.println("AT+CSQ");
	delay(100);

	ShowSerialData();

	char str_[300];
	sprintf(str_, "AT+HTTPPARA=\"URL\",\"http://vetrap.a2hosted.com/index.php?visor=false&latitude=%s&longitude=%s&altitude=%s&time=%s&satellites=%s&speedOTG=%s&course=%s\"", latitude, longitude, altitude, UTCdatetime, satellites, speedOTG, course);
	mySerial.println(str_);
	delay(100);

	mySerial.println("AT+HTTPACTION=0");
	delay(5000);
	ShowSerialData();

	mySerial.println("AT+HTTPREAD");
	delay(500);
	ShowSerialData();

	mySerial.println("");
	delay(100);
}


int8_t get_GPS() {

	int8_t counter;
	long previous;

	while ( mySerial.available() > 0) mySerial.read();

	mySerial.println("AT+CGNSINF");

	counter = 0;
	memset(frame, '\0', sizeof(frame));
	previous = millis();

	do {

		if (mySerial.available() != 0) {
			frame[counter] = mySerial.read();
			counter++;
		}
	}
	while ((millis() - previous) < 2000);

	frame[counter - 3] = '\0';

  // Parses the string
	strtok_single(frame, ": ");
	GNSSrunstatus = atoi(strtok_single(NULL, ","));;
	Fixstatus = atoi(strtok_single(NULL, ","));
	strcpy(UTCdatetime, strtok_single(NULL, ","));
	strcpy(latitude, strtok_single(NULL, ","));
	strcpy(longitude, strtok_single(NULL, ","));
	strcpy(altitude, strtok_single(NULL, ","));
	strcpy(speedOTG, strtok_single(NULL, ","));
	strcpy(course, strtok_single(NULL, ","));
	fixmode = atoi(strtok_single(NULL, ","));
	strtok_single(NULL, ",");
	strcpy(HDOP, strtok_single(NULL, ","));
	strcpy(PDOP, strtok_single(NULL, ","));
	strcpy(VDOP, strtok_single(NULL, ","));
	strtok_single(NULL, ",");
	strcpy(satellitesinview, strtok_single(NULL, ","));
	strcpy(satellites, strtok_single(NULL, ","));
	strcpy(GLONASSsatellitesused, strtok_single(NULL, ","));
	strtok_single(NULL, ",");
	strcpy(cn0max, strtok_single(NULL, ","));
	strcpy(HPA, strtok_single(NULL, ","));
	strcpy(VPA, strtok_single(NULL, "\r"));


	delay(100);

	Serial.println("UTCdatetime");
	Serial.println(UTCdatetime);
	Serial.println("latitude");
	Serial.println(latitude);
	Serial.println("longitude");
	Serial.println(longitude);
	Serial.println("altitude");
	Serial.println(altitude);
	Serial.println("speedOTG");
	Serial.println(speedOTG);
	Serial.println("course");
	Serial.println(course);
	Serial.println("satellites");
	Serial.println("\n\r");

}


void sms() {

	if ((millis() - last_millis) > delay_time ) {
		mySerial.print("AT+CMGL=\"REC UNREAD\"\r\n");
		last_millis = millis();

	}


	if (mySerial.available() > 0) {


		char inchar = mySerial.read();

		Serial.print(inchar);


		if (inchar == '\r' || inchar == '\n') {
			sms_control = 0;
		}

		if (inchar == '#') {
			sms_control++;
			delay(100);
			mySerial.println("AT+CMGD=1,4");
		}


		switch (sms_control) {
			case 1:

			if (inchar == 'O' || inchar == 'N') {
				digitalWrite(led1, HIGH);

			}

			if (inchar == 'O' || inchar == 'F') {
				digitalWrite(led1, LOW);

			}

			if (inchar == 'G' || inchar == 'P' || inchar == 'S') {
				send_SMS();
				delay(100);
			}


			break;


		}

	}
}


void send_SMS()
{
	mySerial.print("AT+CMGS=\"+233205855439\"\r");
	delay(1000);
	mySerial.print("The location of your vehicle is http://maps.google.com/?q="); 
	mySerial.print(latitude);
	mySerial.print(",");
	mySerial.print(longitude);
	delay(1000);
	mySerial.write(0x1A);
}