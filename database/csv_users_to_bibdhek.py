#!/usr/bin/env python

import psycopg2
import sys

# CVS format:
#   First line contains header
#   Nummer;Naam;Voornaam;Klas-Groep;(ignored info)

LAST_NAME = 1
FIRST_NAME = 2
GROUP = 3


def read_value(str):
    return unicode(str.strip(), "utf-8")


def make_username(first_name, last_name, usernames, attemps=0):
    first_part = first_name[:attemps + 1]
    last_name_words = last_name.split(' ')
    last_words = len(last_name_words)
    last_part = ''
    for i in range(0, last_words):
        if i == last_words - 1:
            last_part += last_name_words[i]
        else:
            last_part += last_name_words[i][:1]
    username = first_part + last_part
    username = username.lower()
    username = username[:32]
    if username in usernames:
        if first_part == first_name:
            return None  # impossible to create a valid username
        else:
            return make_username(first_name, last_name, usernames, attemps + 1)
    else:
        return username


def add_user(first_name, last_name, users, usernames):
    username = None
    # Skip if first and last names are empty.
    # It comes probably from an empty line in the csv
    if first_name == '' and last_name == '':
        return None

    full_name = first_name.lower() + last_name.lower()
    if full_name in users:
        return users[full_name]['username']

    username = make_username(first_name, last_name, usernames)
    if username is None:
        print "ERROR: user %s %s has too many name conflicts" % (first_name, last_name)
    else:
        usernames[username] = None
        users[full_name] = {'username': username,
                            'last_name': last_name,
                            'first_name': first_name}
    return username


def add_user_to_group(username, group, groups):
    # Skip if user is None. It comes probably from an empty line in the csv
    if group != '' and username is not None:
        if group in groups:
            groups[group] += [username]
        else:
            groups[group] = [username]


def csv_to_dictionary(users_csv):
    # Note: there is no attempt to verify the username with the database, only
    # with the usernames coming from this large initial import CSV file.
    """Read users csv file and return a dictionary and a set with data to insert."""
    users = {}
    groups = {}
    usernames = {}
    next(users_csv)  # skipping first line (I still need to learn these tricks)
    for line in users_csv:
        user = line.split(';')
        last_name = read_value(user[LAST_NAME])
        first_name = read_value(user[FIRST_NAME])
        group = read_value(user[GROUP])
        username = add_user(first_name, last_name, users, usernames)
        add_user_to_group(username, group, groups)
    return (users, groups)

users_csv = open(sys.argv[1])
print "Parsing csv file"
(users, groups) = csv_to_dictionary(users_csv)

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
    print "Inserting users"
    for key in users:
        user = users[key]
        insert_user = """
        INSERT INTO users (username, first_name, last_name)
        VALUES (%(username)s, %(first_name)s, %(last_name)s)"""
        cursor.execute(insert_user, user)
        conn.commit()

    print "Inserting groups and their users"
    for group in groups:
        insert_group = "INSERT INTO groups (name) VALUES (%(name)s)"
        cursor.execute(insert_group, {'name': group})
        conn.commit()

        for username in groups[group]:
            match_group_username = """
            INSERT INTO group_users
            (user_id, group_id)
            VALUES (
            (SELECT id FROM users WHERE username = %(username)s),
            (SELECT id FROM groups WHERE name = %(group)s))"""
            cursor.execute(match_group_username, {'username': username,
                                                  'group': group})
            conn.commit()

except Exception, e:
    conn.rollback()
    raise e
finally:
    print "Done... closing connection"
    cursor.close()
    conn.close()
