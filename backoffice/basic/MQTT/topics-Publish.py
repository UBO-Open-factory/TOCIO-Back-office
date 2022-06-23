'''
This Python3 programme is provided as sample from
_|_|_|_|_|                    _|           
    _|      _|_|      _|_|_|        _|_|   
    _|    _|    _|  _|        _|  _|    _| 
    _|    _|    _|  _|        _|  _|    _| 
    _|      _|_|      _|_|_|  _|    _|_|   
to send data to an MQTT brocker for module TEST_2
This module have sensor :
- Humidité (%)
- Température (°C)
- Humidité (%)
- Température (°C)
- TestGrand1
___________________________________________________
'''


from paho.mqtt import client as mqtt_client
from time import time
import random

MQTT_topic     = "tocio/mesure/add/TEST_2";
MQTT_brocker   = "mqtt-uof.univ-brest.fr";
MQTT_port      = 1883;
MQTT_username  = "fablab";
MQTT_password  = "Youpi-Tralala_socMEwlI9SH9";
MQTT_client_id = f'TOCIO-mqtt-{random.randint(0, 1000)}'

# Set Connecting Client ID
def connect_mqtt():
	client = mqtt_client.Client(MQTT_client_id)
	client.username_pw_set(MQTT_username, MQTT_password)
	client.connect(MQTT_brocker, MQTT_port)
	return client

# Module TEST_2 have 5 sensor(s),
# so you need to send all the related mesures in same order as defined in TOCIO's Back Office
def getPayload():
	# humidite0 is the 'Humidité' value from your sensor 'DHT11' (as float)
	humidite0 = 0000.00 # <- Your code to read value from sensor goes here 

	# temperature1 is the 'Température' value from your sensor 'DHT11' (as float)
	temperature1 = 0000.00 # <- Your code to read value from sensor goes here 

	# humidite2 is the 'Humidité' value from your sensor 'DHT22' (as float)
	humidite2 = 0000.00 # <- Your code to read value from sensor goes here 

	# temperature3 is the 'Température' value from your sensor 'DHT22' (as float)
	temperature3 = 0000.00 # <- Your code to read value from sensor goes here 

	# testgrand14 is the 'TestGrand1' value from your sensor 'Capttest' (as float)
	testgrand14 = 0000.00 # <- Your code to read value from sensor goes here 

	payload = "{:03.0f}{:+07.2f}{:03.0f}{:+07.2f}{:02.0f}".format(humidite0,temperature1,humidite2,temperature3,testgrand14)
	return payload


if __name__ == '__main__':
	# MQTT Broker Connection
	client = connect_mqtt()
	client.loop_start()

	# Data
	timestamp = time()
	payload   = getPayload()

	# MQTT Message
	message = payload + ";" + str(int(timestamp))

	# MQTT Publish
	client.publish(topic=MQTT_topic, payload=message, qos=1 )

	# MQTT Deconnection
	client.disconnect()