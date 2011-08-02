INSERT INTO groups (name)
	VALUES ('admin');

INSERT INTO users (username, first_name, last_name, email, password)
	VALUES ('saartje', 'Saartje', 'Renaers', 'saartje@renaers.be', 'topsecret');

INSERT INTO user_group (user_id, group_id)
	VALUES ((SELECT id FROM groups WHERE name='admin'),
			(SELECT id FROM users WHERE username='saartje'));

