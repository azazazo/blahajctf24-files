
#!/usr/bin/env python3

import random

FLAG = "[redacted]"
CHARSET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ23456789!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~'

welcome_screen = """Submit the next 100 random bits in newline separated format
Good luck, player!
"""

wait_prompt = "Enter to continue."
guess_prompt = "Submit your random bits: "
incorrect_text = "Your guess was incorrect, it was %s.\n"
correct_text = "Your guess was correct!\n"
success_text = "All bits recovered! The world is saved: %s\n"
rand_gen = random.Random()

def mutate(bits):
    bits = list(bin(bits)[2:])

    for _ in range(25):
        bits[random.randrange(0, len(bits))] = random.choice(CHARSET)

    return "".join(bits)

print(welcome_screen)
input(wait_prompt)

for _ in range(2370):
    rand_num = mutate(rand_gen.getrandbits(32))
    print(rand_num)

for _ in range(100):
    guess=input(guess_prompt)
    actual_guess = str(rand_gen.getrandbits(32))

    try:
        assert guess == actual_guess

    except (UnicodeError, AssertionError):
        print(incorrect_text % (actual_guess))
        break

    else:
        print(correct_text)

else:
    print(success_text % FLAG)
