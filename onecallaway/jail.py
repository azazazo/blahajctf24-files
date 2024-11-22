import threading
del threading

blacklist = [
    '__dict__',
    'exec',
    'eval',
    'breakpoint',
    'open',
    'license',
    'copyright',
    'credits',
]

if __name__ == '__main__':
    code = input('> ')
    
    assert code.isascii(), 'Nope'
    assert all(word not in code for word in blacklist), 'Nope'
    assert code.count('(') <= 1, 'This is a simple pyjail, you only need one call!'
    
    eval(code)
    flag = open('flag.txt').read()

