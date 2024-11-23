def RC4_KeySchedule(key, S):
    j = 0
    for i in range(len(key)):
        j = (j + S[i] + key[i]) % 16
        S[i], S[j] = S[j], S[i]
    return S

def encrypt(message, key):
    state = RC4_KeySchedule(key, list(range(16))) # This was taken from RC4, surely its secure!
    output = []
    for i in range(len(message)):
        m = message[i]
        s = state[i % len(state)]
        output.append(m ^ s)
        if i % len(state) == 0:
            state == RC4_KeySchedule(state, state) # this must be secure, right? You can't figure next xor stream data without knowing the state!
    return output

from os import urandom

key = urandom(16)           
flag = b"The flag is blahaj{?????????????????????????????????????}"
ct = encrypt(flag, key)
print(ct)
"""
[83, 103, 109, 44, 99, 106, 98, 96, 32, 104, 122, 43, 96, 97, 107, 102, 101, 105, 124, 110, 56, 106, 127, 80, 99, 56, 102, 53, 94, 121, 57, 119, 119, 82, 127, 125, 62, 113, 102, 88, 58, 105, 51, 118, 93, 117, 52, 95, 121, 48, 109, 54, 113, 105, 115, 35, 116]
"""