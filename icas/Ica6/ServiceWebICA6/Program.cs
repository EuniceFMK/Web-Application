using Microsoft.Extensions.Hosting;
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

            app.MapGet("/welcome", (string message) =>
            {
                return Results.Ok(new { output = $"{message}" });
            });

            app.MapPost("/data", () =>
            {
                string[] locations = new string[]
                {
                    "Nait Campus",
                    "Southgate",
                    "WEM",
                    "Kingsway"
                };

                Dictionary<string, string[]> menus = new Dictionary<string, string[]>
                {
                    {"Nait Campus", new string[]{"Muffins - $2.29", "Cookies - $1.49","Croissant - $2.19","Iced Coffee - $2.49" }},
                    {"Southgate", new string[] { "Croissant - $2.19", "Iced Coffee - $2.49" }},
                    {"WEM", new string[] { "Pumpkin Spice Iced Capp - $4.29", "Caramel Toffee Cold Brew - $3.99", "Croissant - $2.19" }},
                    {"Kingsway", new string[] { "Muffins - $2.29", "Croissant - $2.19" } }

                };

                return Results.Ok(new { locations = locations, menus = menus });
            });

            app.MapPost("/order", (Info i) =>
            {
                string errormessage = "";
                bool valid = false;
                double cost=0;

                if (i.name.Length == 0)
                {
                    errormessage = "Please enter your name";
                    valid = false;
                }
                else if(i.item== "select")
                {
                    errormessage = "Please select an item from the menu ";
                    valid = false;
                }
                else if (i.quantity <= 0)
                {
                    errormessage = "Enter a valid number of items (at least 1)";
                    valid = false;
                }
                else if (i.payment == "sel")
                {
                    errormessage = "Please select a payment method";
                    valid = false;
                }
                else
                {
                    Console.WriteLine($"The item is: {i.item}");

                    double.TryParse(i.item.Split("$")[1].Trim(), NumberStyles.Any,
                                                   CultureInfo.InvariantCulture, out double price);
                    Console.Write($"The price is: {price}");
                    cost = price * i.quantity;
                    valid = true;
                }

                if (valid == true)
                {
                    Random rand = new Random();
                    int min = rand.Next(5, 31);
                    Console.WriteLine(min);
                    return Results.Ok(new { output = $"Order sucessfully placed", paiement = $"{cost}", valid=true, time = min });
                }
                else
                {
                    return Results.Ok(new { output = $"Order placement denied", paiement = $"{errormessage}" ,valid=false } );
                }
            });
            app.Run();  
        }

        record Info(string name, string item, int quantity, string payment);
    }
}
