import base64
import os
 
FLAG = "blahaj{[REDACTED]}"
 
def encrypt(message):
    pw = []
    enc = ""
    for x in range(len(message)):
        sec = 0
        while sec < 1 or sec > 255:
            sec = ord(os.urandom(1).decode("charmap"))
        pw.append(sec)
        enc += chr((ord(message[x]) + sec) % 256)
    return enc.encode("charmap"), pw

try:
    print("Ever heard of the One-time pad? Surely, you cannot crack this scheme!")
    print("For more info: https://en.wikipedia.org/wiki/One-time_pad")
    while True:
        cmd = input('Command: ')
        if cmd == 'encrypt':
            message = input("Please enter message: ")
            enc, pw = encrypt(message)
            print("Your codebook is:", pw)
            print("Encrypted message is:", base64.b64encode(enc).decode("charmap"))
        elif cmd == 'getflag':
            enc, _ = encrypt(FLAG)
            print("Encrypted flag is:", base64.b64encode(enc).decode("charmap"))
        elif cmd == 'exit':
            break
        else:
            print("Unrecognized command!")
except:
    pass