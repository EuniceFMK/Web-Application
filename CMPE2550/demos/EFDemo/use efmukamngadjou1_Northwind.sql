use efmukamngadjou1_Northwind
GO
select Products.productID, Products.ProductName , coalesce(sum(quantity*[Order Details ].UnitPrice),0)
from Products 
LEFT Join [Order Details] on Products.ProductID = [Order Details].ProductID
where Products.UnitPrice <10
group by Products.ProductID, Products.ProductName
order by ProductName DESC


SELECT * from Categories