from django.shortcuts import render

from django.core.cache import cache
# Create your views here.
from django.http import HttpResponse, JsonResponse
from django.views.decorators.csrf import csrf_exempt
import json
#from Manual_engine import *
from Manual_engine_moran import *

import cmd, sys, argparse
import six

from cs_server import ChatScriptServer

fasttext_path= "../20181119-cleaned-all-words-x300-ws5-min50-minn2-maxn-6-epoch2.bin"

manual_moran_path="../manual_moran.pickle"
manual_thesaurus_path="/home/domyoung/Airi/manual_thesaurus.pickle"
#engine = Manual_engine(manual_path,manual_thesaurus_path,fasttext_path,moran_path,0.85) # load engine
engine = Manual_engine(manual_moran_path,manual_thesaurus_path,fasttext_path,0.85) # load engine

def is_chatscript(Score_int,CS_bool):
	thres = 70
	if(CS_bool == "Y"):
		return True
	else:
		return False
	"""
	if(Score_int>thres):
		if(CS_bool == "Y"):
			return False
		else: #CS_bool == "N"
			return False
	else:#(score<=thres )
		if(CS_bool == "Y"):
			return True
		else: #CS_bool == "N"
			return True
	"""


@csrf_exempt
def answer(request):
	global engine
	global context 
	# return_json_str = json.loads(request.body)

	return_str = request.POST['message'] 

	print(return_str)

	##connecto to Chatscript  #####################################
	CS_server=ChatScriptServer("10.100.0.117", port=1989, username="손님", botname= "Manualbot")


	##result from manual engine #####################################
	score, result = engine.API(return_str) #if the score is -1 don't do chatscript
	"""
	#CS_query : "score   query"
	if(return_str[0]==":"):
		CS_query = return_str
	else :
		CS_query = str(score) + " " + return_str

	print(CS_query,"--------------------------")

	print(type(score))

	if(score == -2): #reset 
		print(result)
		CS_response = CS_server.say(return_str)
		ans=""
		for content in result["contents"]:

			print(content)

			ans+=content+"<br/>"

		return JsonResponse({'text':ans})

	if(CS_query.strip() == ":build MManual"):
		CS_response = CS_server.say(return_str)
		return JsonResponse({'text':"update"})


	if(score != -1):

		#CS_response : "response   Y/N"
		CS_response = CS_server.say(CS_query)

		print("CS_response", CS_response)
		if(len(CS_response.strip())>0): #check if the response length >0
			if is_chatscript(score,CS_response.split()[-1]) :
				return JsonResponse({'text': CS_response[:-2]})   
			
	"""
	##return the answer of Manual Engine##########################################
	ans=""
	for content in result["contents"]:

		print(content)

		ans+=content+"<br/>"



	print(result["photo_id"])
	if(result["photo_id"]==None):
		return JsonResponse({'text': ans})
	else:
		return JsonResponse({'text': ans,'img': str(result["photo_id"])})
