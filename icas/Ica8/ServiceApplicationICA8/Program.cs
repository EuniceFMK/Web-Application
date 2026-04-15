using ServiceApplicationICA8;
using System.Text.RegularExpressions;
namespace ServiceApplicationICA8
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

      

            app.MapGet("/", () =>
            {
                return Results.Ok(new { students = Student.GetStudents() });
            });

            app.MapGet("/StudentInfo", (int id) =>
            {
                return Results.Ok(new { studentsInfo = Student.GetStudentsInfo(id) });
            });

            app.Run();
        }

        public static string CleanInputs(string input)
        {
            // clean your inputs 
            // Removing special chacters in your input
            string cleanString = Regex.Replace(input, "<.*>|&.*?;", string.Empty);

            return cleanString.Trim();  // To remove white spaces

        }
    }
}
