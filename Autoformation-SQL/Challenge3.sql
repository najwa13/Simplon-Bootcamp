--1
SELECT title,author,price FROM library_books; 
--2
SELECT * FROM library_books
WHERE price BETWEEN 100 AND 300;
--3
SElect * FROM library_books
WHERE published_year > 2020;
--4
SELECT * FROM library_books
WHERE title like '%PHP%';
--5
SELECT * FROM library_books
WHERE status = 'Lost'
ORDER BY published_year DESC;
--6
SELECT DISTINCT author FROM library_books;
--7
SELECT UPPER(title) AS upper_title ,ROUND(price) as prounded_price
FROM library_books;