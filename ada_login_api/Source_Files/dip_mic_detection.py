import warnings
import json
warnings.filterwarnings("ignore")

from dejavu import Dejavu
from dejavu.recognize import FileRecognizer, MicrophoneRecognizer

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

	# Fingerprint all the mp3's in the directory we give it
#	djv.fingerprint_directory("C:\Users\Dom\Desktop\\ven\dip_env\music_for_fp", [".mp3",".flac"])

#	# Recognize audio from a file
#	song = djv.recognize(FileRecognizer, "mp3/Sean-Fournier--Falling-For-You.mp3")
#	print "From file we recognized: %s\n" % song

#	# Or recognize audio from your microphone for `secs` seconds
	secs = 12
	song = djv.recognize(MicrophoneRecognizer, seconds=secs)
	if song is None:
		print "Nothing recognized -- did you play the song out loud so your mic could hear it? :)"
	else:
		print "From mic with %d seconds we recognized: %s\n" % (secs, song)

#	# Or use a recognizer without the shortcut, in anyway you would like
#	recognizer = FileRecognizer(djv)
#	song = recognizer.recognize_file("mp3/Josh-Woodward--I-Want-To-Destroy-Something-Beautiful.mp3")
#	print "No shortcut, we recognized: %s\n" % song