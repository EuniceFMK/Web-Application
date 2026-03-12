USE efmukamngadjou1_Northwind
go

SELECT * FROM Employees Where FirstName like 'A%'

if exists (select [name]from sysobjects where [name]='GetProduct')
drop procedure GetProduct
go

create procedure GetProduct
@productID int
as
 select *
 from Products
 where ProductID =@productID
 go

 execute GetProduct 1

