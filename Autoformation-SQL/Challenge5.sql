--1.Total Inventory Value: 
SELECT SUM(price) AS total_price FROM library_books;
--2.Stock Count:
SELECT COUNT(status) AS stock_count from library_books WHERE status='Available';
--3.Price Extremes:
SELECT MAX(price) AS most_expensive ,MIN(price) AS cheapest FROM library_books; 
--4.Average Cost:
SELECT AVG(price) AS average_price FROM library_books;
