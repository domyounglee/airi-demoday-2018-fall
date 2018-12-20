
import pickle

import p_mean_FT as pmeanFT
import fastText
from sklearn.preprocessing import normalize
import numpy as np
from structured_data_moran import *
from pprint import pprint
import re
import collections 
import utils
import random
from subprocess import run, PIPE

p=re.compile('\/|\)|\(| ')

class Manual_engine(object):

	def __init__(self,manual_path,thesaurus_path,fastText_path,threshold):
		self.manual =None
		self.wordvec_dim=None
		self.mean_list=['mean','p_mean_2','p_mean_3']
		self.fastText=None
		self.sent_matrix=None
		self.manual_unfolded=[]
		self.curr_contxt = None #current context id 
		self.top2_contxt=None # top2 context id
		self.threshold=threshold
		self.score = None # the top1 score to give it to chatscript
	
		self.load_manual(manual_path)
		self.load_thesaurus(thesaurus_path)
		self.generate_sentvec(fastText_path)


	def load_manual(self, manual_path):
		with open(manual_path, 'rb') as f:
			# The protocol version used is detected automatically, so we do not
			# have to specify it.
			self.manual = pickle.load(f)
	
	def load_thesaurus(self, thesaurus_path):
		with open(thesaurus_path, 'rb') as f:
			# The protocol version used is detected automatically, so we do not
			# have to specify it.
			utils.manual_thesaurus = pickle.load(f)

	def load_fastText(self, fastText_path):
		self.fastText=fastText.FastText.load_model(fastText_path)
		self.wordvec_dim = self.fastText.get_dimension()




#json response part ####################################################################################################################
	def generate_json_response(self,top1,top2):
		response =collections.OrderedDict()
		#TDL : content None 나올때랑 말단 카테고리로 매칭될때 대처하기 
		if(hasattr(self.manual_unfolded[top1],'parent_idx')):
			if(hasattr(self.manual_unfolded[self.manual_unfolded[top1].parent_idx],'parent_idx')):
				response["contents"] = [self.manual_unfolded[self.manual_unfolded[top1].parent_idx].parent_name +"의 "+ self.manual_unfolded[top1].parent_name+"에 대해 궁금하시군요?"]+\
									 [self.manual_unfolded[top1]._name+"에 대해 설명드릴께요."]+\
									  self.manual_unfolded[top1].contents
			else:
				response["contents"] = [self.manual_unfolded[top1].parent_name+"에 대해 궁금하시군요?"]+\
									 [self.manual_unfolded[top1]._name+"에 대해 설명드릴께요."]+\
									  self.manual_unfolded[top1].contents
		else:
			response["contents"] = [self.manual_unfolded[top1]._name+"에 대해 설명드릴께요."]+\
								  self.manual_unfolded[top1].contents
		

		#if it is device
		if(hasattr(self.manual_unfolded[top1],'methods')):
			if(self.manual_unfolded[top1].methods):
				response["contents"]+=[" <span style='font-weight: bold;'>하위항목은 다음과 같습니다.</span> "]
				for child in self.manual_unfolded[top1].methods:
					response["contents"]+=["&nbsp;&nbsp;&nbsp;  <span class = 'child'  style='cursor:pointer;color:green;'>" + child._name + "</span> "]

		#if it is method 
		elif(hasattr(self.manual_unfolded[top1],'method_children')):
			if(self.manual_unfolded[top1].method_children):
				response["contents"]+=[" <span style='font-weight: bold;'>하위항목은 다음과 같습니다.</span> "]
				for child in self.manual_unfolded[top1].method_children:
					response["contents"]+=["&nbsp;&nbsp;&nbsp;  <span class = 'child'  style='cursor:pointer;color:green;'>" + child._name + "</span> "]

		
		if(hasattr(self.manual_unfolded[top2],'parent_idx')):
			if(hasattr(self.manual_unfolded[self.manual_unfolded[top2].parent_idx],'parent_idx')):
				response["contents"]+=["혹시 이걸 찾으셨나요? <span id = 'recomm'  style='cursor:pointer;color:blue;'>" + self.manual_unfolded[self.manual_unfolded[top2].parent_idx].parent_name +"의 "+self.manual_unfolded[top2].parent_name+" : "+self.manual_unfolded[top2]._name + "</span> "]
			else:
				response["contents"]+=["혹시 이걸 찾으셨나요? <span id = 'recomm'  style='cursor:pointer;color:blue;'>" + self.manual_unfolded[top2].parent_name+" : "+self.manual_unfolded[top2]._name + "</span> "]
		else:
			response["contents"]+=["혹시 이걸 찾으셨나요? <span id = 'recomm'  style='cursor:pointer;color:blue;'>" + self.manual_unfolded[top2]._name+ "</span> "]
			


		response["photo_id"] = self.manual_unfolded[top1].pid

		return response


#sentence vector part ####################################################################################################################
	def generate_sentvec(self,fastText_path):

		self.load_fastText(fastText_path)

		self.sent_matrix=np.empty((0,self.wordvec_dim*len(self.mean_list)))

		for i in range(len(self.manual)):

			#add to manual_unfolded
			self.manual_unfolded.append(self.manual[i])

			#remove josa 
			words = self.manual[i]._josa_rm_name
			words = words.split()

			print("-=====================",words)
			sent_vec=pmeanFT.get_sentence_embedding(words, self.fastText, self.mean_list) #make sentence vector
			self.sent_matrix=np.vstack((self.sent_matrix,sent_vec)) #add to sentence matrix 




			for j in range(len(self.manual[i].methods)):
				
				
				if(self.manual[i].methods[-1]._name != "empty"):
					
					#add to manual_unfolded
					self.manual_unfolded.append(self.manual[i].methods[j])


					#remove josa 
					nemo_words=self.manual[i].methods[j]._josa_rm_name
					nemo_words= nemo_words.split()

					#nemo_words = 2*nemo_words+words
					sent_vec=pmeanFT.get_sentence_embedding(2*nemo_words+words, self.fastText, self.mean_list) #make sentence vector
					self.sent_matrix=np.vstack((self.sent_matrix,sent_vec)) #add to sentence matrix 




				for k in range(len(self.manual[i].methods[j].method_children)):

					#add to manual_unfolded
					self.manual_unfolded.append(self.manual[i].methods[j].method_children[k])

					#remove josa 
					semo_words = p.sub(" ",self.manual[i].methods[j].method_children[k]._josa_rm_name)
					semo_words = semo_words.split()

					sent_vec=pmeanFT.get_sentence_embedding(3*semo_words+nemo_words+words, self.fastText, self.mean_list) #make sentence vector
					self.sent_matrix=np.vstack((self.sent_matrix,sent_vec)) #add to sentence matrix 



		self.sent_matrix = normalize(self.sent_matrix)

#computation part ####################################################################################################################
	#the query is first looked up by the device's family of the curr_idx

	def find_device_ID(self, current_ID):
		if(hasattr(self.manual_unfolded[current_ID],'parent_idx')):
			if(hasattr(self.manual_unfolded[self.manual_unfolded[current_ID].parent_idx],'parent_idx')): #if current_ID is method_child
				return self.manual_unfolded[self.manual_unfolded[current_ID].parent_idx].parent_idx
			else:#if current_ID is method
				return self.manual_unfolded[current_ID].parent_idx
		else:#if current_ID is Device
			return self.manual_unfolded[current_ID].idx
	

	"""
	def compute_curr_sim(self, query):
		


		words = query.split(" ")


		query_vec=pmeanFT.get_sentence_embedding(words, self.fastText ,self.mean_list)#make query vector
		query_vec = np.squeeze(normalize(query_vec.reshape(1,-1)))

		sim_result={}

		device_ID = self.find_device_ID(self.curr_contxt) #get parent id 
		sim_result[device_ID]=self.sent_matrix[device_ID]@query_vec

		for method in self.manual_unfolded[device_ID].methods:
			sim_result[method.idx]=self.sent_matrix[method.idx]@query_vec
			for method_child in method.method_children:
				sim_result[method_child.idx]=self.sent_matrix[method_child.idx]@query_vec
		
		sim_result = sorted(sim_result.items(), key=lambda t: t[1],reverse=True)

		self.score =  int(sim_result[0][1]*100) 
		print("curr sim",self.score)
		if(sim_result[0][1]>self.threshold):
			self.curr_contxt,self.top2_contxt=sim_result[0][0],sim_result[1][0]
			return sim_result[0][0],sim_result[1][0]
		else:

			self.curr_contxt,self.top2_contxt=self.compute_all_sim(query)
			return self.curr_contxt , self.top2_contxt
	"""
	def compute_all_sim(self, query):

		words = query.split(" ")
		

		query_vec=pmeanFT.get_sentence_embedding(words, self.fastText ,self.mean_list)#make query vector
		query_vec = np.squeeze(normalize(query_vec.reshape(1,-1)))
		sim = self.sent_matrix @ query_vec
		rank_idx=(sim).argsort()[::-1]
		top1_idx=rank_idx[0]
		top2_idx=rank_idx[1]
		self.score =  int( sim[top1_idx]*100 )
		print("all_sim",sim[top1_idx])

		return top1_idx,top2_idx

#application part ####################################################################################################################
	def Topk_nn(self,query,k):

		query=query.strip()
		query= utils.replace_query_word(query).strip()
		query = utils.remove_Josa(query).strip()
		
		words = query.split(" ")
		query_vec=pmeanFT.get_sentence_embedding(words, self.fastText ,self.mean_list)#make query vector
		query_vec = np.squeeze(normalize(query_vec.reshape(1,-1)))
		sim = self.sent_matrix @ query_vec
		rank_idx=(sim).argsort()[::-1]
		for i in range(k):
			#print(self.manual_unfolded[rank_idx[i]].parent_name)
			try:
				print("장치명 : ",self.manual_unfolded[rank_idx[i]].parent_name)

				for content in self.manual_unfolded[rank_idx[i]].contents:
					print(content)

				print("pid : ",self.manual_unfolded[rank_idx[i]].pid)

			except KeyError:
				print("장치명 : ",self.manual_unfolded[rank_idx[i]].parent_name)
			
		
				for content in self.manual_unfolded[rank_idx[i]].contents:
					print(content)

				print("pid : ",self.manual_unfolded[rank_idx[i]].pid)


			except:

				print("장치명 : ",self.manual_unfolded[rank_idx[i]]._name)
			
			
				for content in self.manual_unfolded[rank_idx[i]].contents:
					print(content)

				print("pid : ",self.manual_unfolded[rank_idx[i]].pid)

			print(" ")	


	def API(self,query):
		""" return's dictionary
			{내용: contents, photo_id:pid}
		"""
		query=query.strip()
		response = collections.OrderedDict()
		if(query == ":reset"):
			response["contents"] = ["반갑습니다~! 대화형 매뉴얼 : 맹뉴얼 입니다. ","자동차 편의장치에 대해 궁금한점을 물어봐주세요!","예시 : 리모컨 키 건전지 교체하는 방법 알려줄래?" ]
			response["photo_id"] = None
			return -2 , response

		if("매뉴얼" in query or "맹뉴얼" in query or "장치명" in query or "장치 이름" in query or "장치 명" in query):
			response["contents"] = ["저는 자동차 편의장치에 대해 정보를 제공해드리는 챗봇 서비스입니다. ","모니터에 보이는 편의 장치명을 보시고 관련하여 질문주세요~!" ]
			response["photo_id"] = "device"
			return -1, response

		if("그만" in query or "조용" in query  ):
			response["contents"] = ["네" ]
			response["photo_id"] = None
			return -1, response

		if(query == "<<<war>>>"):
			if(self.curr_contxt == None): #initial state
				response["contents"] =  ["우선 질문을 해주세요~!"]
				response["photo_id"] = None
			else:
				if not self.manual_unfolded[self.curr_contxt].wars: # war doesn't exisit
					response["contents"] = ["해당 장치에 대한 경고사항이 존재하지 않습니다. "]
					response["photo_id"] = self.manual_unfolded[self.curr_contxt].pid
				else:
					response["contents"] = [sent for c in self.manual_unfolded[self.curr_contxt].wars for sent in c.contents ]
					response["photo_id"] = self.manual_unfolded[self.curr_contxt].pid
			
			return -1 , response

		if(query == "<<<cau>>>"):
			if(self.curr_contxt == None): #initial state
				response["contents"] = ["우선 질문을 해주세요~!"]
				response["photo_id"] = None
			else:
				if not self.manual_unfolded[self.curr_contxt].caus: # war doesn't exisit
					response["contents"] = ["해당 장치에 대한 주의사항이 존재하지 않습니다. "]
					response["photo_id"] = self.manual_unfolded[self.curr_contxt].pid
				else:
					response["contents"] = [sent for c in self.manual_unfolded[self.curr_contxt].caus for sent in c.contents]
					response["photo_id"] = self.manual_unfolded[self.curr_contxt].pid
			
			return -1, response

		if(query == "<<<top2>>>"):
			self.curr_contxt = self.top2_contxt # top2 becomes the current context 
			if(hasattr(self.manual_unfolded[self.curr_contxt],'parent_idx')): #if it has parent then the top2 becomes its parent 
				
				self.top2_contxt=self.manual_unfolded[self.curr_contxt].parent_idx
				return  -1, self.generate_json_response(self.curr_contxt,self.top2_contxt)
			else:
				_, top2_idx=self.compute_all_sim(self.manual_unfolded[self.curr_contxt]._josa_rm_name) #if it has not 
				self.top2_contxt=top2_idx
				return  self.score, self.generate_json_response(self.curr_contxt,self.top2_contxt)


		if("<<<child>>>" in query ):
			
			query = query[len("<<<child>>>"):]

			if(hasattr(self.manual_unfolded[self.curr_contxt],'methods')):
				
				for child in self.manual_unfolded[self.curr_contxt].methods:
					if(query == child._name):
						self.curr_contxt= child.idx
				
						_, self.top2_contxt=self.compute_all_sim(self.manual_unfolded[self.curr_contxt]._josa_rm_name)
					
						return  self.score, self.generate_json_response(self.curr_contxt,self.top2_contxt)
			
			
			elif(hasattr(self.manual_unfolded[self.curr_contxt],'method_children')):
			
				for child in self.manual_unfolded[self.curr_contxt].method_children:
					if(query == child._name):

						self.curr_contxt= child.idx
					
						_, self.top2_contxt=self.compute_all_sim(self.manual_unfolded[self.curr_contxt]._josa_rm_name)
			
						return self.score, self.generate_json_response(self.curr_contxt,self.top2_contxt)

		print("query:"+query)
		query = utils.limit_token_size(query).strip()#limit the size ( Long sentence is fatal for BOW)
		print("limit query: "+query)
		query= utils.replace_query_word(query).strip()
		print("replaced query: "+query)
		query = utils.remove_Josa(query).strip()#removed the josa with moran 
		print("remove josa query: "+query)
		query = utils.remove_query_word(query).strip()#removed unnecessary
		print("preprocess query:"+query)
		
		self.curr_contxt, self.top2_contxt=self.compute_all_sim(query)
		"""
		if self.curr_contxt == None: #if it is the first query 

			self.curr_contxt, self.top2_contxt=self.compute_all_sim(query)
			
		else:

			self.curr_contxt, self.top2_contxt=self.compute_curr_sim(query)
		"""

		return self.score, self.generate_json_response(self.curr_contxt,self.top2_contxt)


	

if __name__ == '__main__':
	
	fasttext_path= "/home/domyoung/DeepLearning/wordembedding/pretrained/20181101-cleaned-all-words-x300-ws5-min200-minn2-maxn-6-epoch6.bin"
	engine=Manual_engine("./manual_moran.pickle","../manual_thesaurus.pickle",fasttext_path,0.85)

	engine.Topk_nn("스마트키",10)
	"""
	response = engine.API("스마트 키")

	for content in response["contents"]:
		print(content)
	print(response["photo_id"])
	"""
