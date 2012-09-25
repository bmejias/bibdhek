#!/usr/bin/env python

import psycopg2
import sys

# CVS format:
#   First line contains header
#   Nummer;Naam;Voornaam;Klas-Groep;(ignored info)

LAST_NAME   = 1
FIRST_NAME  = 2
GROUP       = 3

def read_value(str):
    return unicode(str.strip(), "utf-8")

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

def csv_to_dictionary(users_csv):
    # Note: there is no attempt to verify the username with the database, only
    # with the usernames coming from this large initial import CSV file.
    """Read users csv file and return dictionary with data to insert."""
    users = {}
    next(users_csv) # skipping first line (I still need to learn these tricks)
    for line in users_csv:
        user        = line.split(';')
        last_name   = read_value(user[LAST_NAME])
        first_name  = read_value(user[FIRST_NAME])
        group       = read_value(user[GROUP])
        if first_name != '' or last_name != '': 
            username    = make_username(first_name, last_name, users)
            if username == None:
               print "ERROR: user %s %s has too many name conflicts" % (first_name, last_name) 
            else:
                users[username] = {LAST_NAME:last_name,
                                   FIRST_NAME:first_name,
                                   GROUP:group}
    return users 

users_csv = open(sys.argv[1])
print "Parsing csv file"
users = csv_to_dictionary(users_csv)

for user in users:
    print user, users[user]

# 
# try:
#     print "Establishing connection to the database"
#     conn = psycopg2.connect(dbname="bibdhek",
#                             host="localhost", port="5433",
#                             user="bd_admin", password="bd_admin")
# except Exception, e:
#     print "ERROR: I am unable to connect to the database"
#     sys.exit(1)
# 
# cursor = conn.cursor()
# 
# print "Inserting values"
# try:
#     for key in books:
#         book = books[key]
#         insert_book = """
#             INSERT INTO books
#             (title, author, cd, collection, level, lang, acquired)
#             VALUES (%(title)s, %(author)s, %(cd)s, %(collection)s,
#                     %(level)s, %(lang)s, %(acquired)s)
#             RETURNING id"""
#         cursor.execute(insert_book, book)
#         new_book = cursor.fetchone()[0]
#         for i in range(1, book['copies'] + 1):
#             insert_copy = """INSERT INTO copies (book_id, status)
#                             VALUES (%s, 'available')""" % new_book
#             cursor.execute(insert_copy)
#         conn.commit()
# except Exception, e:
#     conn.rollback()
#     raise e
# finally:
#     print "Done... closing connection"
#     cursor.close()
#     conn.close()

