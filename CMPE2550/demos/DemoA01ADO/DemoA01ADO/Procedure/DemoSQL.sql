use skelemen_Northwind
go

select * from Employees where FirstName like 'A%'

if exists(select [name] from sysobjects where [name] = 'GetProduct')
	drop procedure GetProduct
go

create procedure GetProduct
	@productID	int
as
	select	* 
	from	Products
	where	ProductID = @productID
go

execute GetProduct 1
go