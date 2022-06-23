'''
Ce script permet de s'abonner aux topics MQTT pour enregistrer les messges contenant une valeur
d'un cpateur

Install dependencies in virtual env :
python3 -m venv venv
pip3 install -r ./requirements.txt
__________________________________________________________________________________________'''
import requests
from requests.structures import CaseInsensitiveDict
from paho.mqtt import client as mqtt
import socket
import json


serverTOCIOLocal = "http://localhost:8888/mesure/add/mqtt"

MQTT_broker     = 'mqtt-uof.univ-brest.fr'
MQTT_port       = 1883
MQTT_topic      = "tocio/#"
MQTT_client_id  = f'TOCIO-mqtt-{socket.gethostname()}'
MQTT_username   = 'fablab'
MQTT_password   = 'Youpi-Tralala_socMEwlI9SH9'





# ----------------------------------------------------------------------------------------
''' Send data to the API
'''
def sendDataToAPI(payload, moduleID):
    # Construct the header
    headers = CaseInsensitiveDict()
    headers["Content-Type"] = "application/json"
    
    # send the request in post format
    print("Try to post to ", serverTOCIOLocal + "/" + moduleID)
    resp = requests.post(serverTOCIOLocal +  "/" + moduleID, headers=headers, data=payload)
    print("Retour du serveur : ",resp.status_code)
    print("text du Retour du serveur : ",resp.text)






# ----------------------------------------------------------------------------------------
''' Subscripting to topics
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
        print("Conection to ", MQTT_broker, "ok")
        client.subscribe(MQTT_topic, qos=1)



# ----------------------------------------------------------------------------------------
''' When a message arrive
'''
def on_message(client, userdata, message):
    # Payload decode
    payload = message.payload.decode('utf-8')
    
    # Extract moduleID from topic like tocio/mesure/add/TEST_2
    moduleID = message.topic.split("/")[-1]

    # Send json structure to TOCIO API
    sendDataToAPI(payload, moduleID)


        


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