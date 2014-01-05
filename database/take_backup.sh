#!/bin/sh

DATE=`date +'%Y-%m-%d-%H%M'`

cd /home/bmejias/bibdhek/database
export PGUSER=bd_admin
export PGPASSWORD=bd_admin

# it requires correct configuration of pg_hba.conf or pgpass to avoid password
pg_dump bibdhek > bibdhek-${DATE}.sql
