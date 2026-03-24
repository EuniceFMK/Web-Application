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

            app.Run();
        }
    }
}
