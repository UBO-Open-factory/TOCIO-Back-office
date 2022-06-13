from logging import captureWarnings
import random
import time
from paho.mqtt import client as mqtt_client


broker = 'mqtt-uof.univ-brest.fr'
port = 1883
client_id = f'TOCIO-mqtt-{random.randint(0, 1000)}'
username = 'fablab'
password = 'fablab'


def connect_mqtt():
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Connected to MQTT Broker!")
        else:
            print("Failed to connect, return code %d\n", rc)
    
    # Set Connecting Client ID
    client = mqtt_client.Client(client_id)
    client.username_pw_set(username, password)
    client.on_connect = on_connect
    client.connect(broker, port)
    return client


def publish(client, topic, message ):
    # Publish message on topic
    result = client.publish(topic, message )

    # result: [0, 1]
    status = result[0]
    if status == 0:
        print(f"Send `{message}` to topic `{topic}`")
    else:
        print(f"Failed to send message to topic {topic}")




# ----------------------------------------------------------------------------------------
if __name__ == '__main__':

    # Broker Connection
    client = connect_mqtt()
    client.loop_start()

    # Topic
    moduleID       = "TESTALEX"
    capteurID      = 5
    capteurOrder   = 1
    grandeurID     = 6
    timestamp      = time.time()
    topic = "grandeur/add/" + str(moduleID) + "/" + str(capteurID) + "/" + str(capteurOrder) + "/" + str(grandeurID)

    # Message
    value = str(18) + ";" + str(int(timestamp))

    # Publication
    publish(client, topic, value)