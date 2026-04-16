using Microsoft.Data.SqlClient;
using System.Data;
using System.Text.RegularExpressions;

namespace Exam041251
{
    
    public class Program
    {
        
        public static void Main(string[] args)
        {
           
            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            var app = builder.Build();

            //CORS needed
            app.UseCors(x => x
                            .AllowAnyMethod()
                            .AllowAnyHeader()
                            .SetIsOriginAllowed(origin => true) // Allow any origin
                            );// allow calling from any website

            app.UseDeveloperExceptionPage(); // developer error messages displayed

           

            app.MapGet("/", () => "Exam 04 ");
            // Write your code here for all parts

            app.MapGet($"/RetrieveItems/", (string sdate, string edate) =>
            {
                return Results.Ok(new { infos = Rest.GetSummary(sdate,edate) });
            });

            app.MapPost("/InsertItem", (AddS record) =>
            {
                string mess = Rest.Additem(record.id, record.name, record.price);
                return Results.Ok(new {  message = mess });
            });

            app.MapPut("/UpdateItem", (AddS record) =>
            {
                string mess = Rest.Updateitem(record.id, record.name, record.price);
                return Results.Ok(new { message = mess });
            });
            app.MapDelete("/DeleteItem/{id}", (int id) =>
            {
                string mess=Rest.DeleteItem(id);
                return Results.Ok(new {  message= mess });
            });
            app.Run();
        }


        // Method for easy troubleshooting
        static Exception GetInnerMostException(Exception ex)
        {
            while (ex.InnerException != null)
                ex = ex.InnerException;

            return ex;
        }
        record AddS(int id, string name, double price);
    }
}
