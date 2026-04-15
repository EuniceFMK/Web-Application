using Microsoft.AspNetCore.Mvc.RazorPages;
using System.Drawing;
using System.Reflection.Metadata.Ecma335;
using System.Xml.Linq;

var builder = WebApplication.CreateBuilder(args);
builder.Services.AddControllers();
var app = builder.Build();

app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));
app.UseDeveloperExceptionPage();

app.MapGet("/", () => "Hello World!");

app.MapGet("/somepage", () => "This is another URL");
app.MapGet("/register", (string name, string color, int age) => $"{name} is {age} years old and likes the {color} color");
app.MapPost("/registerPOST", (Info inputData) => {
    return Results.Ok(new
    {
        output = $"{inputData.name} is {inputData.age} years old and likes the {inputData.color} color"
    });
});

app.Run();

record Info(string name, string color, int age);