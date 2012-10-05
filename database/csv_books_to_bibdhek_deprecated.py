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
    # print "cd %r" % with_cd
    # print "title " + clean_title
    return with_cd, clean_title

def csv_to_dictionary(books_csv, lang):
    """Read books csv file and return dictionary with data to insert."""
    books = {}
    next(books_csv)
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

        if books.has_key(key):
            books[key]['copies'] += 1
        else:
            books[key] = {'author'  : author,
                          'title'   : title,
                          'cd'      : cd,
                          'collection' : collection,
                          'level'   : level,
                          'acquired': acquired,
                          'lang'    : lang,
                          'copies'  : 1}
    return books

# Get language if provided. Set empty otherwise
if len(sys.argv) >= 3:
    lang = sys.argv[2]
else:
    lang = ''

books_csv = open(sys.argv[1])
print "Parsing csv file"
books = csv_to_dictionary(books_csv, lang)

try:
    print "Establishing connection to the database"
    conn = psycopg2.connect(dbname="bibdhek",
                            host="localhost", port="5433",
                            user="bd_admin", password="bd_admin")
except Exception, e:
    print "ERROR: I am unable to connect to the database"
    sys.exit(1)

cursor = conn.cursor()

print "Inserting values"
try:
    for key in books:
        book = books[key]
        insert_book = """
            INSERT INTO books
            (title, author, cd, collection, level, lang, acquired)
            VALUES (%(title)s, %(author)s, %(cd)s, %(collection)s,
                    %(level)s, %(lang)s, %(acquired)s)
            RETURNING id"""
        cursor.execute(insert_book, book)
        new_book = cursor.fetchone()[0]
        for i in range(1, book['copies'] + 1):
            insert_copy = """INSERT INTO copies (book_id, status)
                            VALUES (%s, 'available')""" % new_book
            cursor.execute(insert_copy)
        conn.commit()
except Exception, e:
    conn.rollback()
    raise e
finally:
    print "Done... closing connection"
    cursor.close()
    conn.close()

