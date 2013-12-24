#!/usr/bin/env python

import psycopg2
import sys

# CVS format:
#
# First line contains the header with the following columns
#
# Nummer      Number
# Aantal      NCopies
# Taal        Lang
# Niveau      Level
# Collectie   Collection
# Auteur      Author
# Titel       Title
# Met_CD      CD
# Uitgever    Publisher
# Aankoopjaar Acquired
# Datum       Date
# Opmerkingen Obs
#
# Second line contains formatting instructions, so it needs to be skipped too

NUMBER      = 0
N_COPIES    = 1
LANG        = 2
LEVEL       = 3
COLLECTION  = 4
AUTHOR      = 5
TITLE       = 6
WITH_CD     = 7
PUBLISHER   = 8
ACQUIRED    = 9
BOOK_DATE   = 10
Opmerkingen = 11

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

def with_cd(cd_text):
    cd_text = cd_text.strip()
    cd_text = cd_text.lower()
    return cd_text == 'ja'

def get_lang(lang):
    return lang

def make_book_key(author, title):
    key = "%s %s" % (author, title)
    return key.lower()

# @param copies text defining the amount of copies
# @return integer with the copies with 1 as default
def process_copies(copies):
    if copies == '':
        return 1
    else:
        return int(copies)

def csv_to_dictionary(books_csv):
    """Read books csv file and return dictionary with data to insert."""
    books = {}
    next(books_csv) # Skip header
    next(books_csv) # Skip formatting information
    for line in books_csv:
        book = line.split(';')
        (cd, title) = cd_and_title(read_value(book[TITLE]))
        key = make_book_key(read_value(book[AUTHOR]), title)
        copies = process_copies(read_value(book[N_COPIES]))

        if books.has_key(key):
            books[key]['copies'] += copies
        else:
            books[key] = {'author'  : read_value(book[AUTHOR]),
                          'title'   : title,
                          'cd'      : with_cd(read_value(book[WITH_CD])),
                          'collection': read_value(book[COLLECTION]),
                          'level'   : read_value(book[LEVEL]),
                          'acquired': read_value(book[ACQUIRED]),
                          'lang'    : get_lang(book[LANG]),
                          'copies'  : copies,
                          'publisher': read_value(book[PUBLISHER]),
                          'date'    : read_value(book[BOOK_DATE]),
                          'obs'     : read_value(book[Opmerkingen])
                         }
    return books

def load_books(cursor):
    ID      = 0
    TITLE   = 1
    AUTHOR  = 2
    query = "SELECT * from books"
    cursor.execute(query)
    db_books = cursor.fetchall()
    books = {}
    for book in db_books:
        key = make_book_key(book[AUTHOR], book[TITLE])
        print "DB", key
        books[key] = book[ID]
    return books

#-----------------------------------------
# Parse the CSV input file with new books
#-----------------------------------------
books_csv = open(sys.argv[1])
print "Parsing csv file"
books = csv_to_dictionary(books_csv)

#-----------------------------------------
# Connect to the database
#-----------------------------------------

try:
    print "Establishing connection to the database"
    conn = psycopg2.connect(dbname="bibdhek",
                            host="localhost", port="5432",
                            user="bd_admin", password="bd_admin")
except Exception, e:
    print "ERROR: I am unable to connect to the database"
    sys.exit(1)

cursor = conn.cursor()

try:
    #-----------------------------------------
    # Load existing books
    #-----------------------------------------
    # Won't be loading this time.... just insert everything
    # print "Loading existing books"
    # existing_books = load_books(cursor)

    #-----------------------------------------
    # Update database
    #-----------------------------------------
    print "Updating books"
    for key in books:
        print "Adding a book..."
        book = books[key]
        insert_book = """
            INSERT INTO books
            (title, author, cd, collection, level, lang, acquired, publisher,
            date, obs)
            VALUES (%(title)s, %(author)s, %(cd)s, %(collection)s,
            %(level)s, %(lang)s, %(acquired)s, %(publisher)s,
            %(date)s, %(obs)s)
            RETURNING id"""
        print "SQL Insert query is read:", insert_book % book
        cursor.execute(insert_book, book)
        print "Now fetching the if of the new book"
        new_book = cursor.fetchone()[0]
        for i in range(1, int(book['copies']) + 1):
            insert_copy = """INSERT INTO copies (book_id, status)
                            VALUES (%s, 'available')""" % new_book
            print "Inserting copy %i of %i" % (i, int(book['copies']))
            cursor.execute(insert_copy)
        conn.commit()
except Exception, e:
    conn.rollback()
    raise e
finally:
    print "Done... closing connection"
    cursor.close()
    conn.close()

