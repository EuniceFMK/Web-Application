using System.Text.RegularExpressions;

namespace Demo01ASP
{
    public class Program
    {

        /***********************************************
         * Function Name    : CleanInputs
         * Purpose          : To Sanitize inputs
         * Input            : String
         * Output           : String: cleaned string
         * **********************************************/
        public static string CleanInputs(string input)
        {
            // clean your inputs 
                                // Removing special chacters in your input
            string cleanString = Regex.Replace(input, "<.*>|&.*?;", string.Empty);
            
            return cleanString.Trim();  // To remove white spaces

        }
        
        public static void Main(string[] args)
        {
            // WebApplication is a special class which is provided by MICROSOFT
            // used to configure HTTP pipeline and routes

            var builder = WebApplication.CreateBuilder(args);
            // CORS issue: cross Origin Resouce sharing
            builder.Services.AddControllers();

            var app = builder.Build();

            // Need to fix the CORS problem encountered with PSOT AJAX call
            // will allow web service to be called from any website

            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            //MapGet handles all GET request coming from client side
            // Form submits may be used for GET requests
            // Simple lamda statements are used below for demo purpose only
            // Default endpoint
            app.MapGet("/", () => "ASP DEMO 01");

            //Specific end point
            app.MapGet("/special", () => "When you ask for something special, I will provide it to you");


            // form processing with GET
            app.MapGet("/registerGet", (string getName, string getAge) => $"Form processing {getName} is {getAge} years old;");

            // One would think that passing for POST would be same as the GET, one would be wrong

            // form processing with POST
            app.MapPost("/registerPost", (ClientData cl) =>
            {
                // Something complex
                var response = new object { };

                int charCount = cl.postName.Length;

                // validation and sanitizing your values
                if (charCount <= 0)
                {
                    response = new
                    {
                        name = "Not a valid input",
                        status = "Fail",
                    };
                }
                else
                {

                    // Returning it as a string value
                    //return $"Form processing {cl.postName} is {cl.postAge} years old. You have {charCount} letters in your name";

                    // Creating an object
                    response = new
                    {
                        name = cl.postName,
                        status = "Success",
                        count = charCount
                    };
                }

                // Returning an object back to client
                return response;

            });

            //AJAX call


            app.Run();
        }
        record class ClientData (string postName, string postAge);
        // keyword class is optional here
    }
}
