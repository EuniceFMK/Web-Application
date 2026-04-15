using EFDemo01.ModelNWD;

namespace EFDemo01
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            var app = builder.Build();

            app.MapGet("/", () => "Hello World!");

            /*
             EF- Entity Framework - is object relation mapper
               It is going to create classes for all tables in the Database
               Work with classes and objects  2300

                Step 01: Install Package - Microsoft.EntityFrameworkCore.SqlServer
                Step 02: Install a tool  Tools- NuGet package manger- Package Manager Console

                install-package Microsoft.EntityFrameworkCore.Tools   Make sure to add version if you are targerting any specific version
                
                Reverse Engineering part
                

             */

            app.MapGet("/RetData", () => {
                Console.WriteLine("Inside RetData Endpoint");

                // Create an object of DB
                var db = new DemoDb2550NorthwindContext();

                // LINQ queries 
                // Query Style of LINQ queries
                // PART 1
                //select * from Products
                //UNCOMMENT THE FOLLOWING TO TEST IT WITH QUERY STYLE

             /*    
                  var results = from p in db.Products
                                join c in db.Categories
                                    on p.CategoryId equals c.CategoryId
                                where p.ProductId <= 5 // Filtering
                                orderby p.ProductId descending  // By default it is ascending order use keyword descending for otherwise
                                        //select p; // Similar select *
                                    select new { p.ProductId, p.ProductName, c.CategoryName }; // Specific column

                  return results.ToList();
               */  
                  

                //Method Style
               
                var results = db.Products
                                 .Where(x=>x.CategoryId == x.Category.CategoryId) // JOIN
                                 .Where(x => x.ProductId <= 5)
                                 //.OrderBy(x=>x.ProductId) // Ascending
                                  .OrderByDescending(x=> x.ProductId)
                                   //.Select(x => x);  // Select * from Products
                                   .Select(x => new { x.ProductId, x.ProductName, x.Category.CategoryName });
                 return results.ToList();
                 

            });


            //DML Part - INSERT, UPDATE AND DELETE
            // For testing purpose using MapGet only - make sure to use REST methods
            // insert - Post
            // select - Get
            // Update - Put
            // Delete - Delete

            app.MapGet("InsertCatergory", () =>
            {
                Console.WriteLine("Inside Insert Category End point");

                var db = new DemoDb2550NorthwindContext();

                // Create an object of Category class

                Category newCategory = new Category();

                //Populate the values for the required properties

                newCategory.CategoryName = "Tim Coffee";
                newCategory.Description = "Tim Coffee ";

                try
                {
                    // using add method you can add new object into the list
                    db.Categories.Add(newCategory);

                    //Save the changes in the db
                    db.SaveChanges();  // == commit

                    Console.WriteLine("inserted successfully");

                    return "Inserted Successfully";


                }
                catch (Exception e)
                {
                    Console.WriteLine("Error" + e.Message);
                    return "Error" + e.Message;
                }

            });

            app.MapGet("DeleteCategory/{cid}", (string cid) =>
            {
                Console.WriteLine("Inside Delete Endpoint");

                try
                {
                    int categoryId = int.Parse(cid);

                    var db = new DemoDb2550NorthwindContext();

                    // Try to delete category from DB
                    if (db.Categories.Find(categoryId) is Category c)
                    { // If match found, it will return an object of Category Class 
                        // Remove that object from the List

                        db.Categories.Remove(c);

                        db.SaveChanges();

                        return $"Category with id {categoryId} has been deleted";
                    }
                    else
                    {
                        return $"Category with id {categoryId} has not been found";
                    }

                }
                catch (Exception e)
                {
                    Console.WriteLine("Error" + e.Message);
                    return "Error" + e.Message;
                }
            });

            app.MapGet("/UpdateCategory/{cid}/{CategoryName}", (string cid, string CategoryName) =>
            {
                Console.WriteLine("Inside Update Endpoint");

                try
                {
                    int categoryId = int.Parse(cid);

                    var db = new DemoDb2550NorthwindContext();

                    // Try to update category from DB
                    if (db.Categories.Find(categoryId) is Category c)
                    { // If match found, it will return an object of Category Class 
                        
                        // Update that object from the List

                        c.CategoryName = CategoryName;
                        c.Description = "Desc part is hard coded here";

                        // Update the changed object in the list

                        db.Categories.Update(c);

                        db.SaveChanges();

                        return $"Category with id {categoryId} has been Update";
                    }
                    else
                    {
                        return $"Category with id {categoryId} has not been found";
                    }

                }
                catch (Exception e)
                {
                    Console.WriteLine("Error" + e.Message);
                    return "Error" + e.Message;
                }
            });



            app.Run();
        }
    }
}
