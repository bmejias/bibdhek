#!/usr/bin/env python

# CVS format:
#   First line contains header
#   Level;Collection;Author;Title;Acquired(year);(ignored info)

import psycopg2

try:
    conn = psycopg2.connect(dbname="bibdhek_test",
                            host="localhost", port="5433",
                            user="bd_admin", password="bd_admin")
except Exception, e:
    print "I am unable to connect to the database"

cursor = conn.cursor()
cursor.execute("SELECT datname from pg_database")
rows = cursor.fetchall()

print "\nShow me the databases:\n"
for row in rows:
    print "    ", row[0]

cursor.execute("INSERT INTO foo (title) VALUES (%s) RETURNING id",
               ("This is foo",))
new_foo = cursor.fetchone()[0]

cursor.execute("INSERT INTO bar (foo_id, status) VALUES (%s, %s)",
               (new_foo, "ready"))
conn.commit()

cursor.close()
conn.close()


