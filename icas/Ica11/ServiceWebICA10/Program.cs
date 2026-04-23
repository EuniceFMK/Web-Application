using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Hosting;
using ServiceWebICA6.Models;
using System.Globalization;
using System.Security.Cryptography.X509Certificates;

namespace ServiceWebICA6
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);

            builder.Services.AddControllers();

            var app = builder.Build();

            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader()
                               .SetIsOriginAllowed(origin => true));

            app.UseDeveloperExceptionPage();

            app.MapGet("/", () => "Hello World!");

            app.MapGet("/RetLocation", () =>
            {

                using (var db = new Efmukamngadjou1RestaurantDbContext())
                {
                    var results = db.Locations
                                    .Select(x => new
                                    {
                                        Location = x.LocationName,
                                    })
                                    .OrderByDescending(x => x.Location)
                                    .ToList();
                    return results;
                }
            });

            app.MapPost("/order", (OrderRequest req) =>
            {
                using (var db = new Efmukamngadjou1RestaurantDbContext())
                {
                    var results = db.Orders
                        .Include(o => o.Location)
                        .Where(o => o.Cid == req.CustomerId
                                 && o.Location.LocationName == req.Location)
                        .Select(o => new
                        {
                            orderId = o.OrderId,
                            item = o.Item.ItemName,
                            quantity = o.ItemCount,
                            payment = o.PaymentMethod,
                            price = o.Item.ItemPrice
                        })
                        .ToList();

                    return results;
                }
            });

            app.MapDelete("/deleteOrder/{id}", (int id) =>
            {
                using (var db = new Efmukamngadjou1RestaurantDbContext())
                {
                    try
                    {
                        if (db.Orders.Find(id) is Order o)
                        {
                            db.Orders.Remove(o);
                            db.SaveChanges();
                            return Results.Ok(new
                            {
                                message = "Order deleted Sucessfully"
                            });
                        }
                        return Results.NotFound(new { message = "Order not found" });
                    }
                    catch (Exception ex)
                    {
                        db.ChangeTracker.Clear();
                        return Results.Problem(ex.Message);
                    }
                }

            });

            app.MapGet("/RetItems", () =>
            {
                using (var db = new Efmukamngadjou1RestaurantDbContext())
                {
                    return db.Items.Select(i => new
                    {
                        itemId = i.Itemid,
                        itemName = i.ItemName
                    }).OrderBy(i => i.itemName)
                      .ToList();
                }
            });

            //Place new Order
            app.MapPost("/placeOrder", (NewOrderRequest req) =>
            {
                using (var db = new Efmukamngadjou1RestaurantDbContext())
                {
                    if(! db.Customers.Any(c=> c.Cid == req.CustomerId))
                    {
                        return Results.BadRequest(new { message = "Invalid Customer ID" });
                    }

                    Random rnd = new Random();
                    int pickup = rnd.Next(5, 31);

                    Order newOrder = new Order()
                    {
                        Cid = req.CustomerId,
                        Itemid = req.ItemId,
                        ItemCount = req.Quantity,
                        PaymentMethod = req.Payment,
                        Locationid = req.LocationId
                    };
                    db.Orders.Add(newOrder);
                    db.SaveChanges();
                    return Results.Ok(new
                    {
                        message = "Order placed successfully",
                        estimatedPickup = pickup + " minutes",
                        orderId = newOrder.OrderId
                    });
                }
            });

            app.MapPut("/updateOrder", (UpdateOrderRequest req) =>
            {
                using (var db = new Efmukamngadjou1RestaurantDbContext())
                {

                    if (db.Orders.Find(req.OrderId) is Order o)
                    {
                        o.Itemid = req.ItemId;
                        o.ItemCount = req.Quantity;
                        o.PaymentMethod = req.Payment;

                        db.Orders.Update(o);
                        db.SaveChanges();
                        return Results.Ok(new
                        {
                            message = "Order updated successfully"
                        });
                    }
                    return Results.NotFound(new
                    {
                        message = "Order not found"
                    });
                }
            });

            app.Run();
        }

        record NewOrderRequest(int CustomerId, int ItemId, int Quantity, string Payment, int LocationId);
        record UpdateOrderRequest(int OrderId, int ItemId, int Quantity, string Payment);
        record OrderRequest(int CustomerId, string Location);
    }
}
