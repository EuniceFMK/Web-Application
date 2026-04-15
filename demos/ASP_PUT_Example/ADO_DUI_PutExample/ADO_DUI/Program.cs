using ADO_DUI.DAC;

namespace ADO_DUI
{
    public class Program
    {
        public static void Main(string[] args)
        {
			var builder = WebApplication.CreateBuilder(args);

			// Remember to add the controllers for gaining access to POST/PUT/DELETE
			builder.Services.AddControllers();

			var app = builder.Build();

			// Need to configure the application to accept requests from other domains
			app.UseCors(x => x.AllowAnyHeader()     // Allow all header information from request 
								.AllowAnyMethod()   // Allow PUT/POST/DELETE/etc - HTTP Methods
								.SetIsOriginAllowed(origin => true));   // All domains permitted

			// You could extract the connection string from the appsettings.json file
			// using the following command.
			//string connection = builder.Configuration.GetConnectionString("NorthwindConn");

			// Default.  When you browse to the app this is where you land
			app.MapGet("/", () =>
			{
				// Ask the static class we built to retrieve the Customers in Canada
				// THis method leads to a plain text query implementation
				List<List<string>> data = NorthwindADO.GetCustomers("Canada");

				// return the raw data from the DB to the client.
				// In some cases you may wish to do more processing with the data,
				// which is fine.  Once you have the data it is yours to play with.
				return new { Data = data };
			});


			// You reach this method by adding "/byProcedure" to the default URL
			// If name/value pairs were included in the URL, they are mapped to the 
			// variables in the lambda variable parenthesis.
			app.MapGet("/byProcedure", (int value1, int value2) =>
			{
				// Ask the static class we built to retrieve the Customers in Brazil
				// This method leads to a plain text query implementation
				List<List<string>> data = NorthwindADO.GetCustomersByProcedure("Brazil");

				// As above, this is just the raw data.
				return new { Data = data };
			});


			// You are already familiar with using MapPost and how a record is used 
			// to map to the data passed in the HTTP packet payload. 
			app.MapPost("/makeNew", (Info record) => {


			});


			// MapPut also uses a record to map to the payload of the HTTP packet
			app.MapPut("/changeStuff", (Info stuff) => {

				// This time we are asking the DB to change country values with the data 
				// passed from the client.  It would be a good Idea to perform some data
				// validation here the same as you would with a POST operation.
				string mess = NorthwindADO.ChangeCustomerCountries(stuff.cOld, stuff.cNew);

				// Status message being returned to client.
				return Results.Ok(new { message = mess });

			});


			// MapDelete accepts data as follows.  You can attempt to use the record 
			// as with POST and PUT, but many web servers strip away the body of the
			// HTTP packet and ignore it when the method is DELETE.
			app.MapDelete("/remove/{id}/{name}", (int id, string name) =>
			{



			});


			app.Run();
		}
	}

	record Info(string cOld, string cNew);
}
