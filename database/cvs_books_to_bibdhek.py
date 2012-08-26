#!/usr/bin/env python

import psycopg2
import sys

# CVS format:
#   First line contains header
#   Level;Collection;Author;Title;Acquired(year);(ignored info)

LEVEL       = 0
COLLECTION  = 1
AUTHOR      = 2
TITLE       = 3
ACQUIRED    = 4

def read_value(str):
    return unicode(str.strip(), "utf-8")

def cd_and_title(book_title):
    """Searches for '+ CD' and returns the clean title and a boolean for CD."""
    with_cd = False
    if (book_title.rstrip())[-4:] == "+ CD":
        clean_title = (book_title.rstrip())[:-4].strip()
        with_cd = True
    elif (book_title.rstrip())[-3:] == "+CD":
        clean_title = (book_title.rstrip())[:-3].strip()
        with_cd = True
    else:
        clean_title = book_title.strip()
    print "cd %r" % with_cd
    print "title " + clean_title
    return with_cd, clean_title

# Get language if provided. Set empty otherwise
if len(sys.argv) >= 3:
    lang = sys.argv[2]
else:
    lang = ''

# Read books csv file and return dictionary with data to insert
books_csv   = open(sys.argv[1])
books       = {}

for line in books_csv:
    book        = line.split(';')
    level       = read_value(book[LEVEL])
    collection  = read_value(book[COLLECTION])
    author      = read_value(book[AUTHOR])
    full_title  = read_value(book[TITLE])
    (cd, title) = cd_and_title(full_title)
    acquired    = read_value(book[ACQUIRED])
    key         = "%s %s" % (author, title)
    key         = key.lower()
    print "KEY " +key

    if books.has_key(key):
        books[key]['copies'] += 1
    else:
        books[key] = {'author'  : author,
                      'title'   : title,
                      'cd'      : cd,
                      'collection' : collection,
                      'level'   : level,
                      'acquired': acquired,
                      'language': lang,
                      'copies'  : 1}

print books

# Connect to the database
# try:
#     conn = psycopg2.connect(dbname="bibdhek_test",
#                             host="localhost", port="5433",
#                             user="bd_admin", password="bd_admin")
# except Exception, e:
#     print "I am unable to connect to the database"
# 
# cursor = conn.cursor()
# cursor.execute("SELECT datname from pg_database")
# rows = cursor.fetchall()
# 
# print "\nShow me the databases:\n"
# for row in rows:
#     print "    ", row[0]
# 
# cursor.execute("INSERT INTO foo (title) VALUES (%s) RETURNING id",
#                ("This is foo",))
# new_foo = cursor.fetchone()[0]
# 
# cursor.execute("INSERT INTO bar (foo_id, status) VALUES (%s, %s)",
#                (new_foo, "ready"))
# conn.commit()
# 
# cursor.close()
# conn.close()


