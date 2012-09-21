CREATE TABLE books
(
	id			serial		PRIMARY KEY,
	title		text		NOT NULL,
	author		text		NOT NULL,
	cd			boolean		DEFAULT FALSE,
	collection	text,
	level		varchar(20),
	lang		char(2),
	date		date,
	isbn		varchar(16),
	cover		varchar(16),
	publisher 	text,
	obs			text,
	acquired	varchar(16)
);

CREATE TABLE copies
(
	id			serial		PRIMARY KEY,
	book_id		serial		REFERENCES books(id),
	status		varchar(16)	NOT NULL,
	code		varchar(16),
	position	varchar(16),
	type		varchar(16)
);

CREATE TABLE users
(
	id			serial		PRIMARY KEY,
	username	varchar(32)	UNIQUE,
	first_name	text		NOT NULL,
	last_name	text		NOT NULL,
	email		text,
	group 		text,
	password 	varchar(64)
);

CREATE TABLE groups
(
	id		serial		PRIMARY KEY,
	name	varchar(32)	UNIQUE
);

CREATE TABLE group_users
(
	id			serial	PRIMARY KEY,
	user_id		serial	REFERENCES users(id),
	group_id	serial	REFERENCES groups(id)
);

CREATE TABLE loans
(
	id			serial		PRIMARY KEY,
	copy_id		serial		REFERENCES copies(id),
	user_id		serial		REFERENCES users(id),
	status		varchar(16)	NOT NULL,
	date_out	date		NOT NULL,
	date_return	date,
	date_in		date,
	cd			boolean		DEFAULT FALSE,
	deposit		numeric(4, 2)	DEFAULT 0,
	fine		numeric(4, 2)	DEFAULT 0,
	paid		numeric(4, 2)	DEFAULT 0
);

CREATE TABLE rules
(
	id		serial		PRIMARY KEY,
	rule	varchar(32)	NOT NULL,
	amount	int,
	note	text
);

