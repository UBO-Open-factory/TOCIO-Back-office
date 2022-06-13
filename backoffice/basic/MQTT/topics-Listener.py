import random
import time
from paho.mqtt import client as mqtt_client
import socket


MQTT_broker = 'mqtt-uof.univ-brest.fr'
MQTT_port = 1883
MQTT_topic = "grandeur/add/#"
MQTT_client_id = f'TOCIO-mqtt-{socket.gethostname()}'
MQTT_username = 'fablab'
MQTT_password = 'fablab'






# ----------------------------------------------------------------------------------------------------------------------
''' Abonnement aux topics
'''
def on_connect(client, userdata, flags, rc):
    # Abonnement aux topics
    client.subscribe(MQTT_topic)



# ----------------------------------------------------------------------------------------------------------------------
''' A la réception d'un message MQTT
'''
def on_message(client, userdata, message):
    # Decodage de la payload au format JSON
    payload = message.payload.decode('utf-8')

    # Analyse du message dans le topîc
    if( message.topic == MQTT_topic ):
        print("payload", payload)
        print("topic", message.topic)
        


# ----------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------
if __name__ == '__main__':

    # Initialize the MQTT client 
    client = mqtt_client.Client(MQTT_client_id)
    client.username_pw_set(MQTT_username, MQTT_password)
    client.on_connect = on_connect
    client.on_message = on_message

    # Connection
    client.connect(host=MQTT_broker, port=MQTT_port)
    try:
        client.loop_forever()
    except KeyboardInterrupt:
        print("Stop")
        client.disconnect()
        client.loop_stop()