
import re 
import os 
import pickle 
from subprocess import run, PIPE

moran_path="../moran4corpus"

def remove_Josa(query):
	print("not removed Josa",query)
	print(moran_path)
	p = run(["./moran", 'word'], stdout=PIPE,input=query ,encoding="utf8",cwd=moran_path)
	print("removed Josa",p)
	return p.stdout.strip() 
	

class warning(object):
	def __init__(self):
		self.contents=[]

class caution(object):
	def __init__(self):
		self.contents=[]
		

class method_child(object):
	def __init__(self, method_child_name, josa_rm_method_child_name):
		self._name = method_child_name
		self._josa_rm_name=josa_rm_method_child_name
		self.parent_name = None
		self._josa_rm_parent_name = None
		self.contents = [] # content of ▶
		self.pid = None
		self.wars=[]
		self.caus=[]
		self.idx=None
		self.parent_idx = None
		

class method(object):
	def __init__(self,method_name,josa_rm_method_name):
		self._name= method_name
		self._josa_rm_name=josa_rm_method_name
		self.parent_name = None
		self._josa_rm_parent_name = None
		self.contents=[] #contents of ■
		self.method_children=[] #add  method child instance
		self.pid = None
		self.wars=[]
		self.caus=[]
		self.idx=None
		self.parent_idx = None
	
class device(object):
	def __init__(self,device_name,josa_rm_device_name):
		self._name = device_name 
		self._josa_rm_name=josa_rm_device_name
		self.contents=[] #contents of ■
		self.methods = [] #add method instance 
		self.pid = None
		self.wars=[]
		self.caus=[]
		self.idx=None


#parse the manual . 
if __name__ == "__main__":
	devices=[]
	dev_or_met_or_chi=0 #select method or method_child to save the contents or pid : device == 0 ,  method == 1 , child == 2
	cau_switch=0 #cau switch
	war_switch=0 #war switch
	cau=None #caution instance 
	war=None # warning instance 
	line=None
	#extract tags from manual and print
	with open("../tagging_santafe.txt","r") as f:
		i=0
		for line in f.readlines():
			line = line.strip()
			if(len(line.split())<1):
				continue
			#print(line)
			#add device
			if("__DEL__" in line.split()[0]):
				continue

			if("<<<bm>>>" in line.split()[0]):
				s=len("<<<bm>>>")
				name=remove_Josa(line[s:])
				dev=device(line[s:],name)
				dev.idx=i
				devices.append(dev)
				dev_or_met_or_chi=0

				cau_switch=0 #cau switch
				war_switch=0 #war switch
				i+=1
				continue

			#add method in its device
			if("<<<sm>>>" in line.split()[0]):
				s=len("<<<sm>>>")
				name=remove_Josa(line[s:])
				met=method(line[s:],name)
				met.idx=i
				met.parent_idx = devices[-1].idx
				met.parent_name = devices[-1]._name
				devices[-1].methods.append(met)
				dev_or_met_or_chi=1

				cau_switch=0 #cau switch
				war_switch=0 #war switch
				i+=1
				continue

			#add method_child in its method
			if("<<<st>>>" in line.split()[0]):
				s=len("<<<st>>>")
				#if there is no nemo
				if(len(devices[-1].methods)==0):
					devices[-1].methods.append(method("empty","empty"))
				name=remove_Josa(line[s:])	
				chi=method_child(line[s:],name)
				chi.idx=i
				chi.parent_idx = devices[-1].methods[-1].idx #it can be None
				chi.parent_name = devices[-1].methods[-1]._name #it can be None
				devices[-1].methods[-1].method_children.append(chi)
				dev_or_met_or_chi=2

				cau_switch=0 #cau switch
				war_switch=0 #war switch
				i+=1
				continue

			#add pid in method
			if("<<<pid>>>" in line.split()[0]):
				s=len("<<<pid>>>")
				if(dev_or_met_or_chi == 0 ):
					devices[-1].pid = line[s:]
				elif(dev_or_met_or_chi == 1):
					devices[-1].methods[-1].pid= line[s:]
				else:
					devices[-1].methods[-1].method_children[-1].pid = line[s:]
				continue



			if("<<<war>>>" in line.split()[0]):
				war=warning()

				#save the previous one 
				if(dev_or_met_or_chi==0):
					devices[-1].wars.append(war)
				elif(dev_or_met_or_chi==1):
					devices[-1].methods[-1].wars.append(war)
				else:
					devices[-1].methods[-1].method_children[-1].wars.append(war)

				
				war_switch=1
				cau_switch=0
				continue

			if("<<<cau>>>" in line.split()[0]):
				cau=caution()

				#save the previous one 
				if(dev_or_met_or_chi==0):
					devices[-1].caus.append(cau)
				elif(dev_or_met_or_chi==1):
					devices[-1].methods[-1].caus.append(cau)
				else:
					devices[-1].methods[-1].method_children[-1].caus.append(cau)

			
				war_switch=0
				cau_switch=1
				continue

			#add the contents to war or cau
			if(war_switch ==1):
				war.contents.append(line)
				continue
			if(cau_switch ==1):
				cau.contents.append(line)
				continue


			#append it the the given instances
			if(dev_or_met_or_chi == 0):
				devices[-1].contents.append(line)
			if(dev_or_met_or_chi == 1):
				devices[-1].methods[-1].contents.append(line)
			if(dev_or_met_or_chi == 2):
				devices[-1].methods[-1].method_children[-1].contents.append(line)


	#save the remainer cau and war
	if(dev_or_met_or_chi==0):
		devices[-1].wars.append(war)
		devices[-1].caus.append(cau)
	elif(dev_or_met_or_chi==1):
		devices[-1].methods[-1].wars.append(war)
		devices[-1].methods[-1].caus.append(cau)
	else:
		devices[-1].methods[-1].method_children[-1].wars.append(war)
		devices[-1].methods[-1].method_children[-1].caus.append(cau)


	with open('manual_moran.pickle', 'wb') as f:
		# Pickle the 'data' dictionary using the highest protocol available.
		pickle.dump(devices, f, pickle.HIGHEST_PROTOCOL)