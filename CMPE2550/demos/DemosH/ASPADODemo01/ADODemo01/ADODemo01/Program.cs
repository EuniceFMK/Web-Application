using Microsoft.Data.SqlClient;
using System.Transactions;
using static System.Runtime.InteropServices.JavaScript.JSType;

namespace ADODemo01
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            var app = builder.Build();

            // Default end point
            app.MapGet("/", () => "ADO Demo 01");

            // Define a connection string that includes information about the database
                                               
            string connectionString =   "Server = data.cnt.sast.ca,24680;" +
                                        "Database = demo_db2550_Northwind;" +
                                        "User Id = demoUser;" +
                                        "Password = temP2020#;" +
                                        "Encrypt = false;"; 

            app.MapGet("/RetrieveEmployee", () =>
            {
                Console.WriteLine("Inside Ret Emp Endpoint");

                try  // Try to open a connection and SQL stuff so using try catch block just in case 
                {

                    // Step 1 : Establish a connetion to DB
                    // Use SQLConnection class to opne a connection
                    // Pass connection string

                    SqlConnection connection = new SqlConnection(connectionString);

                    // Step 2: Open the connection
                    connection.Open();
                    
                    Console.WriteLine("Connection is open now");

                    //Step 3: Prepare your query 

                    string query = "select * from Products where ProductID = @PID";

                    // Step 4: Execute SQL query directly from C# code
                    // SqlCommand class object is requied
                    // Need to pass query and connection object

                    SqlCommand command = new SqlCommand(query, connection);

                    // Add value for the parameter
                    command.Parameters.AddWithValue("@PID", 1);  
                    // Hard coded the value, but your can use your value coming
                    // from client side after cleaning

                    // Step 5: Run your query 
                    SqlDataReader reader =  command.ExecuteReader();  // ExecuteReader() to run your retrieval queries 
                    // result set back

                    // Step 6: Reading data from returned data set
                    while (reader.Read())  // will return false if no data or end of data set
                    { // Access data using reader['ColumnName']
                        Console.WriteLine($"{reader["ProductId"]}  {reader["ProductName"]}");
                        
                        // You have data, you can return it back to user using JSON object
                        // LIST, DICTIONARY, CLASS, ARRAY

                    }

                    connection.Close(); // To Close the connection


                    // TEST it with Stored Procedure
                    /*
                     *  Step 1: Create a stored procedure in the db
                     *  Step 2: Call your stored procedure from C# code
                     *  
                     *  
                     *  Stoered Procedure Definition: Make sure to create it in your DB fist
                     *  
                     *  create or alter procedure GetProducts (@PID int)
                        as
	                        select * from Products
	                        where ProductID = @PID

                        go
                     */

                    connection.Open();  // Open your connection

                    Console.WriteLine("Testing it with Stored procedure");

                    query = "GetProducts";

                    command = new SqlCommand(query, connection);

                    // Change the command type to SP  -- IMPORTANT POINT
                    command.CommandType = System.Data.CommandType.StoredProcedure;

                    // Make sure to add arguments
                    command.Parameters.AddWithValue("@PID", 2);

                    // Run your SP 
                    reader = command.ExecuteReader();

                    // Read the data 

                    while (reader.Read())
                    {
                        // Access the values for specific column cell using reader["columnName"]

                        Console.WriteLine($" {reader["ProductID"]}  {reader["ProductName"]}");

                    }

                }
                catch (Exception e)
                {
                    Console.WriteLine("Error while working database " + e.Message);
                }

            });
            

            // DEMO 02 - For DML part

            // End Point for managing delete employee
            // app.MapDelete

            // Make sure to use REST methods
            app.MapGet("/DeleteEmployee", () =>{
                Console.WriteLine("Inside the delete employee end point");
                SqlConnection con = new SqlConnection(builder.Configuration.GetConnectionString("DefaultConnection"));
                                                        // Bringing connection string from appsettings.json file

                con.Open(); // to open the actual connection

                SqlTransaction tracsaction = con.BeginTransaction();  // NEW Start transaction;

                try
                {
                    //prepare your query

                    string query = "delete from Employees where EmployeeId = @empID";

                    SqlCommand command = new SqlCommand(query, con, tracsaction);  // New Transaction object

                    command.Parameters.AddWithValue("@empID", 18); // Make sure to change EMP ID here

                    // Execute your query 
                    //ExecuteNonQuery() - For running DML queries
                    // number of rows affected
                    int rowAffected = command.ExecuteNonQuery();


                    //All good at this point- commit changes
                    tracsaction.Commit();  // Save changes

                    Console.WriteLine($" Number of Employees Deleted {rowAffected}");

                    return $" Number of Employees Deleted {rowAffected}";

                }
                catch (Exception e)
                {
                    tracsaction.Rollback();
                    Console.WriteLine("Error while doing database operations" + e.Message);

                    return "Error while doing database operations" + e.Message;
                }

            });
            app.Run();
        }
    }
}
