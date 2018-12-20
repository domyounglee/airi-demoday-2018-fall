# AI manual 맹뉴얼 
AIRI 2018 가을 데모데이 프로젝트 
---  

## 폴더 설명
1. AIRI_webdemo : 매뉴얼 엔진 서버 가 있는 폴더
* html : web UI 가 있는 폴더
* Manual_thesaurus : 매뉴얼 장치명 동의서 사전 생성하는 폴더
* moran4corpus : 입력 문장과 학습 문장들의 조사를 제거하기 위한 형태소 분석기 폴더
* 상위 디렉토리 파일들 
 * 20181119-cleaned-all-words-... : 학습된 fasttext 모델
 * CSforManual.tar.xz : Chatscript 모듈 
 * manual_moran.pickle : 매뉴얼 구조체 
 * manual_thesaurus.pickle : 매뉴얼 
 * santafe_category.txt : 매뉴얼 section 제목 모음
 * santafe.pdf : 매뉴얼 원본 파일 
 * tagging_santafe.txt : 원본을 텍스트파일로 변환시킨 파일 

## 프로젝트 실행환경
* Linux ubuntu 18.04
* Python 3.6
* python requirements
 * Numpy 
 * sklearn
 * django 1.8

----
## 실행방법 
1. 시소러스 만들기 
python Manual_thesaurus.py 
2. 구조체 만들기 
python structured_data_moran.py
3. 파이썬 서버 실행하기 
python ./manage.py runsslserver 0.0.0.0:8000
4. 웹 페이지 열기 
[데모](https://10.100.0.159/ChatScript/hanchatscript2.php)

5. **주의사항 **(웹페이지 접속하기 전에 접속 허용해야할 URL들)
 * 파이썬 서버 
 : https://10.100.0.159:8000/Manual_chatbot/answer/
 * 네이버 TTS
 : https://10.100.0.157:8889/tts/naver
 * 구글 TTS 
 : https://10.100.0.3:8080/transcribe/websocket  
 * 웹페이지 열고 플레이버튼 한번 누르기


## demo video 
https://youtu.be/wswVLtEJzk4
