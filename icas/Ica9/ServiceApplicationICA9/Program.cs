using ServiceApplicationICA8;
using System.Globalization;
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


            app.MapGet("/ClassIds", () =>
            {
                return Results.Ok(new { classInfo = Student.GetClassID() });
            });

            app.MapDelete("/remove/{id}", (int id) =>
            {
                Student.DeleteStudent(id);
                return Results.Ok(new { students = Student.GetStudents() });
            });

            app.MapPut("/update/{id}", (Info record) =>
            {
                string mess;
                if (!int.TryParse(record.shid, out int validShid))
                    mess= "School ID must be a number";
                else
                    mess=Student.EditStudent(record.id, record.fname, record.lname, validShid);
                return Results.Ok(new { students = Student.GetStudents(), message= mess });
            });

            app.MapPost("/add", (AddS record) =>
            {
                string mess=Student.AddStudent(record.fname, record.lname, record.shid, record.classid);
                return Results.Ok(new { students = Student.GetStudents(), message= mess });
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
    record Info(int id, string fname, string lname, string shid);
    record AddS(string fname, string lname, int shid, string classid);
}
