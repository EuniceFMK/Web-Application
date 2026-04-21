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
                        .Include(o => o.Location) // 🔥 important si relation
                        .Where(o => o.Cid == req.CustomerId
                                 && o.Location.LocationName == req.Location)
                        .Select(o => new
                        {
                            orderId = o.OrderId,
                            item = o.Item.ItemName,
                            quantity = o.ItemCount,
                            payment = o.PaymentMethod
                        })
                        .ToList();

                    return results;
                }
            });

            app.Run();
        }

        // 🔹 BON MODEL POUR ICA10
        record OrderRequest(int CustomerId, string Location);
    }
}
