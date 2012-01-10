INSERT INTO groups (name)
	VALUES ('admin');

INSERT INTO users (username, first_name, last_name, email, password)
	VALUES ('saartje', 'Saartje', 'Renaers', 'saartje@renaers.be', 'topsecret');

INSERT INTO users (username, first_name, last_name, email, password)
	VALUES ('bib_admin', 'Admin', 'van de Bib', 'noreply@bibdhek.be', 'secret');

INSERT INTO group_users (user_id, group_id)
	VALUES ((SELECT id FROM groups WHERE name='admin'),
			(SELECT id FROM users WHERE username='saartje'));

