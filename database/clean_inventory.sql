-- This script removes the data from tables:
-- 
-- . books
-- . copies
-- . loans
-- 
-- The other tables remain untouch. The goal is to remove test data for books,
-- but keep the users 

DELETE FROM loans;

DELETE FROM copies;

DELETE FROM books;
