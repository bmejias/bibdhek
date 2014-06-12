-- Get how many books where lent in total
SELECT COUNT(id) FROM loans;

-- Get the most lent books

SELECT st.counts AS counts, bo.title AS title, bo.author AS author FROM books bo, (SELECT COUNT(lc.loan_id) AS counts, lc.book_id FROM (SELECT l.id AS loan_id, b.id AS book_id FROM loans l, books b, copies c WHERE l.copy_id=c.id AND c.book_id=b.id) lc GROUP BY lc.book_id) st WHERE st.book_id=bo.id ORDER BY counts DESC;

-- Get how many books did each user lent, sorted from max to min

SELECT l.counts, u.username, u.first_name, u.last_name FROM users u, (SELECT COUNT(l.id) AS counts, l.user_id AS user_id FROM loans l GROUP BY l.user_id) l WHERE u.id=l.user_id ORDER BY l.counts DESC;
