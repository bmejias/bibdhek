CREATE USER 'bd_admin' PASSWORD 'bd_admin' createuser;

CREATE TABLE books
(
	id		bigserial unique primary_key,
	title	text	not null,
	author	text	not null,
	cd		bool	false,
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
	id			bigserial unique primary_key,
	book		bigserial not null,
	status		varchar(16) not null,
	code		varchar(16),
	position	varchar(16),
	type		varchar(16)
);

CREATE TABLE users
(
	id			bigserial unique primary_key,
	firtst_name	text not null,
	last_name	text not null,
	email		text,
	password 	varchar(64)
);

CREATE TABLE groups
(
	id		serial	unique primary_key,
	name	varchar(32)
);

CREATE TABLE user_group
(
	id		bigserial unique primary_key,
	user	bigserial references users(id),
	group	serial references groups(id)
);

CREATE TABLE loans
(
	id			bigserial unique primary_key,
	material	bigserial references materials(id),
	user		bigserial references users(id),
	date_out	date not null,
	date_in		date,
	status		varchar(16) not null,
	money		int
);

CREATE TABLE rules
(
	id		serial unique primary_key,
	rule	varchar(32) not null,
	amount	int,
	note	text
);

