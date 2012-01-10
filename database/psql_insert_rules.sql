/* default amount of days a book is lent */
INSERT INTO rules (rule, amount, note)
	VALUES ('lend', 21, 'days');

/* amount of delayed days to apply a fine */
INSERT INTO rules (rule, amount, note)
	VALUES ('fine_days', 1, 'days');

/* amount of cents to be payed for every fine_days */
INSERT INTO rules (rule, amount, note)
	VALUES ('fine_money', 10, 'cents');

/* deposit in case of taken media copies */
INSERT INTO rules (rule, amount, note)
	VALUES ('deposit', 100, 'cents');

/* maximum amount of books that a user can lend */
INSERT INTO rules (rule, amount, note)
	VALUES ('max_books', 3, 'books');

