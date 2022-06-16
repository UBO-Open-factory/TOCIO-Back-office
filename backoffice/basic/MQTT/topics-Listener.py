'''
Ce script permet de s'abonner aux topics MQTT pour enregistrer les messges contenant une valeur
d'un cpateur

Dépendances : 
pip3 install paho-mqtt
__________________________________________________________________________________________'''
import requests
from requests.structures import CaseInsensitiveDict
from paho.mqtt import client as mqtt
import socket
import json


serverTOCIOLocal = "http://localhost:8888/mesure/add/mqtt/"

MQTT_broker     = 'mqtt-uof.univ-brest.fr'
MQTT_port       = 1883
MQTT_topic      = "tocio/#"
MQTT_client_id  = f'TOCIO-mqtt-{socket.gethostname()}'
MQTT_username   = 'fablab'
MQTT_password   = 'Youpi-Tralala_socMEwlI9SH9'





# ----------------------------------------------------------------------------------------
''' split a topic in differents parts, constrcut json structure an return it.

    topic should looks like: tocio/mesure/add/TESTALEX/5/1/6
                            ignore   0    1    2      3 4 5
'''
def jsonConstruuct(topic, message):
    values = message.split(";")

    # extract elements from the topic
    elems = topic.split("/")[-6:]  # last 6 elements from the begining

    # construct the json structure from elements
    return {
            "moduleID"      : elems[2],
            "capteurID"     : elems[3],
            "ordre"         : elems[4],
            "IdGrandeur"    : elems[5],
            "value"         : values[0],
            "timestamp"     : values[1]
    }




# ----------------------------------------------------------------------------------------
''' send data (in JSON ofmrat) to the TOCIO's API
@bug : Le JSON doit être avec des doubles cotte et en UTF-8
'''
def sendDataToAPI(dataDict):
    dataJson = json.dumps(dataDict)

    print("dataJson:", dataJson)

    # Construct the header
    headers = CaseInsensitiveDict()
    headers["Content-Type"] = "application/json"
    
    # send the request in post format
    print("Try to post to ", serverTOCIOLocal +  dataDict['moduleID'])
    resp = requests.post(serverTOCIOLocal +  dataDict['moduleID'], headers=headers, data=dataJson)
    print(resp.status_code)






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
    
    # split message and payload to json structure
    data = jsonConstruuct( message.topic, message.payload.decode('utf-8'))

    # Send json structure to TOCIO API
    sendDataToAPI(data)


        


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