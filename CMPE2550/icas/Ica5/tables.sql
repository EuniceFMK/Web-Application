DROP TABLE IF EXISTS authors;
CREATE TABLE authors (
    au_id VARCHAR(11) PRIMARY KEY,
    au_lname VARCHAR(40) NOT NULL,
    au_fname VARCHAR(20) NOT NULL,
    phone CHAR(12) NOT NULL DEFAULT 'UNKNOWN'
) 

use efmukamngadjou1_Pubs
GO

delete from titleauthor where title_id liKe 'BU1032'
DELETE FROM titles where title_id like 'BU1032'

UPDATE titles
SET 
    title = 'New Title',
    type  = 'business',
    price = 19.99
WHERE title_id = 'BU1032';

Select distinct type from titles