'''
Ce script permet de s'abonner aux topics MQTT pour enregistrer les messges contenant une valeur
d'un cpateur

Dépendances : 
pip3 install paho-mqtt
__________________________________________________________________________________________'''
import random
import time
from paho.mqtt import client as mqtt
import socket


MQTT_broker = 'mqtt-uof.univ-brest.fr'
MQTT_port = 1883
MQTT_topic = "#"
MQTT_client_id = f'TOCIO-mqtt-{socket.gethostname()}'
MQTT_username = 'fablab'
MQTT_password = 'Youpi-Tralala_socMEwlI9SH9'






# ----------------------------------------------------------------------------------------------------------------------
''' Subscripbing to topics
 rc (Result Code): 
    0: Connection successful 
    1: Connection refused – incorrect protocol version 
    2: Connection refused – invalid client identifier 
    3: Connection refused – server unavailable 
    4: Connection refused – bad username or password 
    5: Connection refused – not authorised 
    6-255: Currently unused.
'''
def on_connect(client, userdata, flags, rc):

    if( rc != 0):
        print("Not connected to ", MQTT_broker, "on topic", MQTT_topic)
    else :
        # Subscripbing to topic
        client.subscribe(MQTT_topic, qos=1)




# ----------------------------------------------------------------------------------------------------------------------
''' When a message arrive
'''
def on_message(client, userdata, message):
    # Payload decode
    payload = message.payload.decode('utf-8')
    
    print("message.topic:", message.topic)
    print("payload:", payload)

    # Analyse du message dans le topîc
    if( message.topic == MQTT_topic ):
        print("payload", payload)
        print("topic", message.topic)
        


# ----------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------
if __name__ == '__main__':

    # Initialize the MQTT client
    client = mqtt.Client(MQTT_client_id)
    client.username_pw_set(MQTT_username, MQTT_password)
    client.on_connect = on_connect
    client.on_message = on_message

    # Connection
    client.connect(host=MQTT_broker, port=MQTT_port)


    try:
        client.loop_forever()
    except KeyboardInterrupt:
        print("Keyboard interupt detected -> Stop")
        client.disconnect()
        client.loop_stop()