if exists (select [name] from sysobjects 
					where [name] = 'GetCustomersByCountry')
	drop procedure GetCustomersByCountry
go

create procedure GetCustomersByCountry
	@country nvarchar(15)
as
	SELECT	CustomerID as 'Customer ID', 
			CompanyName as 'Company Name', 
			ContactName as 'Contact Name' 
	FROM	Customers 
	WHERE	Country = @country
go


execute GetCustomersByCountry 'Brazil'
go


