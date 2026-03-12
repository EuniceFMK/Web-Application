using DemoADO.Classes;

var builder = WebApplication.CreateBuilder(args);
builder.Services.AddControllers();
var app = builder.Build();

app.MapGet("/", () =>
{
    return Results.Ok( new { employees = NorthwindADO.GetEmployees() });
});

app.MapGet("/product", (int product) =>
{
    return Results.Ok(new { productInfo = NorthwindADO.GetProductInfo(product) });
});

app.Run();
