
import socket

class ChatScriptServer(object):
    def __init__(self, servername, port=1024, username=None, botname=None):
        self.servername = servername
        self.port = port
        self.username = username or '.'
        self.botname = botname or ''

    def say(self, message):
        to_say = "{username}\0{botname}\0{message}\0".format(username=self.username, botname=self.botname, message=message)
       
        connection = socket.create_connection((self.servername, self.port))

        connection.send(to_say.encode())#Returns the number of bytes sent and the input must be a byte 
        response =  ""
        while True:
            chunk = connection.recv(512)
            if chunk:
                response += chunk.decode()
            else:
                break

        return response
