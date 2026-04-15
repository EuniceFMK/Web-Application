using EFDemo.Models;

namespace EFDemo
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);

            var app = builder.Build();

            app.MapGet("/", () => "Hello World!");

            app.MapGet("/RetData", () =>
            {
                using (var db = new Efmukamngadjou1NorthwindContext())
                {
                    var results = db.Products
                                    .Where(x=> x.UnitPrice<10)
                                    .Select(x=> new
                                    {
                                        Pid = x.ProductId,
                                        Product = x.ProductName,
                                        Sum = x.OrderDetails.Sum(y=>y.Quantity*y.UnitPrice)
                                    })
                                    .OrderByDescending(x=>x.Product)
                                    .ToList();
                    return results;
                }
            });

            app.MapPost("/InsertCategory", () =>
            {
                Category c = new Category();
                c.CategoryName = "Test Category";
                c.Description = "Test Category for Demo";
                using (var db = new Efmukamngadjou1NorthwindContext())
                {
                    try
                    {
                        db.Categories.Add(c);
                        db.SaveChanges();
                        return Results.Ok("Insert successful");
                    }
                    catch (Exception ex)
                    {
                        db.ChangeTracker.Clear();
                        return Results.Problem(ex.Message);
                    }
                }
            });

            app.MapPut("/UpdateCategory", () =>
            {
                int id = 9;
                using (var db = new Efmukamngadjou1NorthwindContext())
                {
                    try
                    {
                        if(db.Categories.Find(id) is Category c)
                        {
                            c.CategoryName = "UpdatedName";
                            c.Description = "Updated description for Demo ";
                            db.Categories.Update(c);
                            db.SaveChanges();
                            return Results.Ok("Update successful");
                        }
                        else
                        {
                            return Results.NotFound("Category not found");
                        }
                        
                    }
                    catch (Exception ex)
                    {
                        db.ChangeTracker.Clear();
                        return Results.Problem(ex.Message);
                    }
                }
            });


            app.MapDelete("DeleteCategory", () =>
            {
                int id = 9;
                using (var db = new Efmukamngadjou1NorthwindContext())
                {
                    try
                    {
                        if (db.Categories.Find(id) is Category c)
                        {
                            
                            db.Categories.Remove(c);
                            db.SaveChanges();
                            return Results.Ok("Delete successful");
                        }
                        else
                        {
                            return Results.NotFound("Category not found");
                        }

                    }
                    catch (Exception ex)
                    {
                        db.ChangeTracker.Clear();
                        return Results.Problem(ex.Message);
                    }
                }
            });

            app.Run();
        }
    }
}
