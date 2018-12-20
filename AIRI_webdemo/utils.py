from subprocess import run, PIPE

moran_path="../moran4corpus"

removal_words=["어떻게","알리다", "주다","말하다","이야기","대하다","관하다","뭣","궁금", "보여", "보이다","좀"]

manual_thesaurus=None

def remove_Josa( query):

	p = run(["./moran", 'word'], stdout=PIPE,input=query ,encoding="utf8",cwd=moran_path)

	return p.stdout.strip() 

def remove_query_word( query):
	for word in removal_words:
		query = query.replace(word,"")

	return query

def limit_token_size(query):
	limited_query= ""
	tokens=query.split()
	for i,token in enumerate(tokens):
		if(i >= 10):
			break
		limited_query += token + " "
	return limited_query

def replace_query_word(query):
	query = query.replace(".","")
	query_len= len(query.split())

	if(len(manual_thesaurus)==0):
		print("load thesaurus")
		raise IndexError
	else:

		for k,v_list in manual_thesaurus.items():
			for v in v_list:
				
				if(v in query):
					query = query.replace(v,(query_len)*(" " + k)) # stress the entity word by its query length
					break

	return query



## add replace_query_word