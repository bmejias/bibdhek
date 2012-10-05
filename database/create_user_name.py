#!/usr/bin/env python

def make_username(first_name, last_name, users, attemps=0):
    first_part = first_name[:attemps+1]
    last_name_words = last_name.split(' ')
    last_words = len(last_name_words)
    last_part = ''
    for i in range(0, last_words):
        if i == last_words-1:
            last_part += last_name_words[i]
        else:
            last_part += last_name_words[i][:1]
    username = first_part + last_part
    username = username.lower()
    username = username[:32]
    if users.has_key(username):
        if first_part == first_name:
            return None # impossible to create a valid username
        else:
            return make_username(first_name, last_name, users, attemps + 1)
    else:
        return username

users = {}
test_names = [('boriss', 'mejias'),
              ('BORISS', 'Mejias'),
              ('bOriss', 'MEJIAS'),
              ('the', 'moor'),
              ('the', 'moor'),
              ('the', 'moor'),
              ('the', 'moor'),
              ('tsjoriks', 'van zwartwater'),
              ('tsjoriks', 'van zwart water'),
              ('tsjoriks', 'van zwart water')]

for person in test_names:
    username = make_username(person[0], person[1], users)
    users[username] = 'foo'

print users.keys()
