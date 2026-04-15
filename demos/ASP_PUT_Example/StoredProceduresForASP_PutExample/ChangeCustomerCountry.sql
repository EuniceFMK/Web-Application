if exists (select [name] from sysobjects 
					where [name] = 'ChangeCustomerCountry')
	drop procedure ChangeCustomerCountry
go

create procedure ChangeCustomerCountry
	@countryOld		nvarchar(15) = null,
	@countryNew		nvarchar(15) = null,
	@status			nvarchar(100)	output
as
if @countryOld is null
begin
	set @status = 'The value passed in for countryOld was null!'
	return -1
end

if @countryNew is null
begin
	set @status = 'The value passed in for countryNew was null!'
	return -2
end

	update	skelemen1_Northwind.dbo.Customers
	set		Country = @countryNew
	where	Country = @countryOld

if @@error <> 0
begin
	set @status = 'There was an unknown error when attempting to change countries!'
	return -3
end

set @status = 'The change operation was successful!'
return 0
go



declare @message nvarchar(100) = ''
declare @ret int = 0

execute @ret = ChangeCustomerCountry 'France', 'Canada', @message output

select @ret, @message
go