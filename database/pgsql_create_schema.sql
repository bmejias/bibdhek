CREATE TABLE books
(
	id		serial		PRIMARY KEY,
	title	text		NOT NULL,
	author	text		NOT NULL,
	cd		boolean		DEFAULT FALSE,
	level	varchar(20),
	lang	char(2),
	date	date,
	isbn	varchar(16),
	cover	varchar(16),
	publisher text,
	obs		text
);

CREATE TABLE materials
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
	password 	varchar(64)
);

CREATE TABLE groups
(
	id		serial		PRIMARY KEY,
	name	varchar(32)	UNIQUE
);

CREATE TABLE group_users
(
	id			serial,
	user_id		serial	REFERENCES users(id),
	group_id	serial	REFERENCES groups(id),
	PRIMARY KEY (user_id, group_id)
);

CREATE TABLE loans
(
	id			serial		PRIMARY KEY,
	material_id	serial		REFERENCES materials(id),
	user_id		serial		REFERENCES users(id),
	date_out	date		NOT NULL,
	date_in		date,
	status		varchar(16)	NOT NULL,
	money		int
);

CREATE TABLE rules
(
	id		serial		PRIMARY KEY,
	rule	varchar(32)	NOT NULL,
	amount	int,
	note	text
);

