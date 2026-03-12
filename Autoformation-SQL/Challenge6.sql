--1.Status Report:
SELECT status,COUNT(title) AS total_books FROM library_books GROUP BY status;
--2.Author Performance: 
SELECT author,SUM(price) AS total_value FROM library_books GROUP BY author;
--3.The Filter: 
SELECT author,SUM(price) AS total_value 
FROM library_books 
GROUP BY author 
HAVING total_value >= 300;