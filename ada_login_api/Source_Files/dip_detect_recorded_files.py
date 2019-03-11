import warnings
import json
import sys
warnings.filterwarnings("ignore")

from dejavu import Dejavu
from dejavu.recognize import FileRecognizer, MicrophoneRecognizer

#brez tega mece napako, windows error 2
from pydub import AudioSegment
AudioSegment.converter = "C:\wamp64\www\\ada_login_api\Source_Files\\resources\\ffmpeg-3.4.1\\bin\\ffmpeg.exe"

config = {
	"database": {
		"host":"127.0.0.1",
		"user":"dipUser",#"root",
		"passwd":"dipPass",#"",
		"db":"dejavu"
	}
}

if __name__ == '__main__':

	# create a Dejavu instance
	djv = Dejavu(config)

	filename = sys.argv[1]
	#filename = "C:\wamp64\www\\ada_login_api\Source_Files\\1519093480449.m4a"
	#print filename
	song = djv.recognize(FileRecognizer,filename)
	print song["song_name"]

	#d = "C:\Users\Dom\Desktop\\"

	#mDir = "C:\Users\Dom\Desktop\wavRecorderTest\\"
'''
	song = djv.recognize(FileRecognizer,mDir+"iwlwu (1).wav")
	print "detecting iwlwu (1).wav"
	print song["song_name"]
	
	song = djv.recognize(FileRecognizer,mDir+"iwlwu (2).wav")
	print "detecting iwlwu (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"iwlwu (3).wav")
	print "detecting iwlwu (3).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"iwlwu (4).wav")
	print "detecting iwlwu (4).wav"
	print song["song_name"]

	
	song = djv.recognize(FileRecognizer,mDir+"A_beautiful_song (1).wav")
	print "detecting A_beautiful_song (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"A_beautiful_song (2).wav")
	print "detecting A_beautiful_song (2)"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"A_beautiful_song (3).wav")
	print "detecting A_beautiful_song (3)"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"A_beautiful_song (4).wav")
	print "detecting A_beautiful_song (4)"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"A_beautiful_song (5).wav")
	print "detecting A_beautiful_song (5)"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"A_beautiful_song (6).wav")
	print "detecting A_beautiful_song (6)"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Alejandro (1).wav")
	print "detecting Alejandro (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Alejandro (2).wav")
	print "detecting Alejandro (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Alejandro (3).wav")
	print "detecting Alejandro (3).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Alejandro (4).wav")
	print "detecting Alejandro (4).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"End Run (1).wav")
	print "detecting End Run (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"End Run (2).wav")
	print "detecting End Run (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"End Run (3).wav")
	print "detecting End Run (3).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"gold_and_oden (1).wav")
	print "detecting gold_and_oden (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"gold_and_oden (2).wav")
	print "detecting gold_and_oden (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"ImProudOfYou (1).wav")
	print "detecting ImProudOfYou (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"ImProudOfYou (2).wav")
	print "detecting ImProudOfYou (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"IwasLostWithoutYou (1).wav")
	print "detecting IwasLostWithoutYou (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"IwasLostWithoutYou (2).wav")
	print "detecting IwasLostWithoutYou (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"IwasLostWithoutYou (3).wav")
	print "detecting IwasLostWithoutYou (3).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"IwasLostWithoutYou (4).wav")
	print "detecting IwasLostWithoutYou (4).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"IwasLostWithoutYou (5).wav")
	print "detecting IwasLostWithoutYou (5).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"SuicideMission (1).wav")
	print "detecting SuicideMission (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"SuicideMission (2).wav")
	print "detecting SuicideMission (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"SuicideMission (3).wav")
	print "detecting SuicideMission (3).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"SuicideMission (4).wav")
	print "detecting SuicideMission (4).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Uunan (1).wav")
	print "detecting Uunan (1).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Uunan (2).wav")
	print "detecting Uunan (2).wav"
	print song["song_name"]

	song = djv.recognize(FileRecognizer,mDir+"Uunan (3).wav")
	print "detecting Uunan (3).wav"
	print song["song_name"]
'''