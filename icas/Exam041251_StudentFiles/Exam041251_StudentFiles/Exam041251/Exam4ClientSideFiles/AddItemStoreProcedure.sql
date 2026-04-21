if exists (select [name] from sysobjects 
					where [name] = 'AddItem')
	drop procedure AddItem
go

create procedure AddItem
	@itemid		    int,
    @iname	        varchar(85),
    @iprice         float,
	@status			nvarchar(100)	output
as
-- if @itemid is null
-- begin
-- 	set @status = 'The value passed in for item id was null!'
-- 	return -1
-- end


if @iname is null
begin
	set @status = 'The value passed in for item name was null!'
	return -2
end

if @iprice is null
begin
	set @status = 'The value passed in for item price was null!'
	return -3
end


	insert into efmukamngadjou1_RestaurantDB.dbo.Items (Itemid,ItemName,ItemPrice)
	values		( @itemid, '@iname', @iprice)
	

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

execute @ret = AddItem  19, "Canada", 15, @message output

select @ret, @message
go