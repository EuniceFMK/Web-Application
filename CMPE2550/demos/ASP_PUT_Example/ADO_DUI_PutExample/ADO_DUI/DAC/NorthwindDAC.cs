using Microsoft.Data.SqlClient;

namespace ADO_DUI.DAC
{
	public static class NorthwindADO
	{
		// Define a connection string.  This could be made to be adjustable through 
		// a public property to the outside, in which case you could extract a
		// connection string from the appsettings.json file.  See command in the 
		// Program.cs file.	
		static string northwindConnection = "Server=data.cnt.sast.ca,24680;Database=skelemen1_Northwind;User Id=skelemen1;Password=New_123;Encrypt=false";

		// Retrieve Customers from the specified country
		// NOTE:	This method uses plain text queries in conjunction with the 
		//			SqlConnection, SqlCommand, and SqlParameter objects
		public static List<List<string>> GetCustomers(string country)
		{
			// Collection for holding returned data
			List<List<string>> retData = new List<List<string>>();

			// Create connection object... large object so put in using 
			// block for immediate marking for disposal if you do not need
			// it persisted once the database operation has been completed.
			// Create outside of using block if you need the connection to
			// remain open after the data has been extracted.
			using (SqlConnection conn = new SqlConnection(northwindConnection))
			{
				conn.Open();

				// Set up the query.  It is advised to test the query in SSMS
				// so you know it works, then embed it here.  Be careful with the 
				// syntax when transposing.
				string query = "SELECT CustomerID as 'Customer ID', CompanyName as 'Company Name', ContactName as 'Contact Name' FROM Customers WHERE Country = @country";

				// Create a command object using the created connection and the query
				// defined above.
				using (SqlCommand comm = new SqlCommand(query, conn))
				{
					// We must define the parameter we have in the select query
					SqlParameter param = new SqlParameter();
					param.Direction = System.Data.ParameterDirection.Input; // Default
					param.ParameterName = "@country";  // Make sure this matches 
													   // Check the database table to ensure that you are using the exact
													   // data type and length (if applicable) to avoid mismatch exceptions
					param.SqlDbType = System.Data.SqlDbType.NVarChar;
					param.Size = 15;
					param.Value = country;      // Use the value passed into the method
					comm.Parameters.Add(param); // Add the parameter into the Command object

					// SQLDataReader may not be instantiated by itself... it must be accepted
					// from a database query execution as shown in the following using block,
					// but the using block itself is not mandatory.
					using (SqlDataReader reader = comm.ExecuteReader())
					{
						// Extract all of the column headers and add them to a List<string>
						// which will in turn be added to the result set collection.
						retData.Add(new List<string>());
						for (int i = 0; i < reader.FieldCount; ++i)
							retData[0].Add(reader.GetName(i));

						// For all of the data rows in the returned data set, add each row
						// into its own List<string> and then add that to the result set 
						// collection
						while (reader.Read())
						{
							List<string> temp = new();
							for (int i = 0; i < reader.FieldCount; ++i)
								temp.Add(reader[i].ToString());
							retData.Add(temp);
						}

					}
				}
			}

			// return the processed result set
			return retData;
		}


		// This method does exactly what the above method does, but the operation
		// is carried out by executing a stored procedure in the database.  This
		// allows an extra layer of data validation and other operational safeguards
		// to be used in the database.
		// NOTE:  Only changes below are commented
		public static List<List<string>> GetCustomersByProcedure(string country)
		{
			List<List<string>> retData = new List<List<string>>();

			using (SqlConnection conn = new SqlConnection(northwindConnection))
			{
				conn.Open();

				// We no longer need the plain text query
				//string query = "SELECT CustomerID as 'Customer ID', CompanyName as 'Company Name', ContactName as 'Contact Name' FROM Customers WHERE Country = @country";
				using (SqlCommand comm = new SqlCommand())
				{
					// We must configure the Command object to use a stored procedure
					comm.CommandType = System.Data.CommandType.StoredProcedure;
					// Maks ure this matchs the name of the procedure in the database
					comm.CommandText = "GetCustomersByCountry";
					comm.Connection = conn;


					SqlParameter param = new SqlParameter();
					param.Direction = System.Data.ParameterDirection.Input;
					param.ParameterName = "@country";
					param.SqlDbType = System.Data.SqlDbType.NVarChar;
					param.Size = 15;
					param.Value = country;
					comm.Parameters.Add(param);


					using (SqlDataReader reader = comm.ExecuteReader())
					{
						retData.Add(new List<string>());
						for (int i = 0; i < reader.FieldCount; ++i)
							retData[0].Add(reader.GetName(i));

						while (reader.Read())
						{
							List<string> temp = new();
							for (int i = 0; i < reader.FieldCount; ++i)
								temp.Add(reader[i].ToString());
							retData.Add(temp);
						}

					}
				}
			}
			return retData;
		}

		// This method demonstrates a data manipulation operation
		// We will be setting a new country value for all customers in a designated
		// old country.
		public static string ChangeCustomerCountries(string countryOld, string countryNew)
		{
			// No need for a collection... by default none is returned.  If you
			// did want a collection returned then you would call a retrieval
			// operation after a successful data manipulation.
			string message = "";

			using (SqlConnection conn = new SqlConnection(northwindConnection))
			{
				conn.Open();

				using (SqlCommand comm = new SqlCommand())
				{
					// Still using a stored procedure
					comm.CommandType = System.Data.CommandType.StoredProcedure;
					comm.CommandText = "ChangeCustomerCountry";
					comm.Connection = conn;

					// We'll need as many parameters as necessary to send the
					// data we wish to have stored in the database
					SqlParameter cOld = new SqlParameter();
					cOld.Direction = System.Data.ParameterDirection.Input;
					cOld.ParameterName = "@countryOld";
					cOld.SqlDbType = System.Data.SqlDbType.NVarChar;
					cOld.Size = 15;
					cOld.Value = countryOld;
					comm.Parameters.Add(cOld);

					SqlParameter cNew = new SqlParameter();
					cNew.Direction = System.Data.ParameterDirection.Input;
					cNew.ParameterName = "@countryNew";
					cNew.SqlDbType = System.Data.SqlDbType.NVarChar;
					cNew.Size = 15;
					cNew.Value = countryNew;
					comm.Parameters.Add(cNew);

					// Sometimes we'll want information back from the stored
					// procedure.  Output parameters to the rescue.  Make sure
					// you build the procedure and test it with the output
					// parameter and return value parameters in SSMS beofre you 
					// try running it from here.
					SqlParameter status = new SqlParameter();
					status.Direction = System.Data.ParameterDirection.Output;
					status.ParameterName = "@status";
					status.SqlDbType = System.Data.SqlDbType.NVarChar;
					status.Size = 100;
					status.Value = string.Empty;
					comm.Parameters.Add(status);

					SqlParameter ret = new SqlParameter();
					ret.Direction = System.Data.ParameterDirection.ReturnValue;
					ret.SqlDbType = System.Data.SqlDbType.Int;  // Only type allowed
					ret.Value = 0;
					comm.Parameters.Add(ret);

					// Unforeseen errors can occur, especially with data manipulation
					// operations.  Highly advised to use a try / catch
					try
					{
						// This is used for all INSERT / UPDATE / DELETE
						comm.ExecuteNonQuery();

						// using the output message parameter.  If this had been a
						// different data type than string, an appropriate cast
						// would be required.
						message = status.Value.ToString() + $" - Return Code : {(int)ret.Value}";
					}
					catch (Exception ex)
					{
						// If an unknown error occurs, grab the message from the DB and return
						// it to the caller
						message = ex.Message;
					}


				}
			}

			return message;
		}
	}
}
