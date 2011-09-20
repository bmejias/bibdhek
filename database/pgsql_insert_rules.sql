/* default amount of days a book is lent */
INSERT INTO rules (rule, amount, note)
	VALUES ('lend', 14, 'days');

/* amount of delayed days to apply a fine */
INSERT INTO rules (rule, amount, note)
	VALUES ('fine_days', 1, 'days');

/* amount of cents to be payed for every fine_days */
INSERT INTO rules (rule, amount, note)
	VALUES ('fine_money', 10, 'cents');

/* deposit in case of taken media material */
INSERT INTO rules (rule, amount, note)
	VALUES ('deposit', 50, 'cents');

