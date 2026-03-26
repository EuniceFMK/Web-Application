using DemoA01ADO.DAC;

namespace DemoA01ADO
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            var app = builder.Build();

            app.MapGet("/", () => {

                return NorthwindDAC.GetEmployees();
            
            });

			app.MapGet("/productInfo", (int productID) => {

				return Results.Ok( new { info = NorthwindDAC.GetProduct(productID) });

			});

			app.Run();
        }
    }
}
