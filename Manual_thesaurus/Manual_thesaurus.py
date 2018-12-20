import pickle
import collections
Manual_thesaurus=collections.OrderedDict()
with open("./Manual_Thesaurus","r") as f:
	i=0
	for line in f.readlines():
		if(len(line.split())!=0):
			if(i%2==0):
					print(line)
					device=line.strip()
			else:
				synonym=[]
				for w in line.strip().split(","):
					
					synonym.append(w.strip())
				if(device not in Manual_thesaurus):
					Manual_thesaurus[device]=synonym
			i+=1
print(Manual_thesaurus)
with open('../manual_thesaurus.pickle', 'wb') as f:
# Pickle the 'data' dictionary using the highest protocol available.
	pickle.dump(Manual_thesaurus, f, pickle.HIGHEST_PROTOCOL)