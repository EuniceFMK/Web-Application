using Microsoft.Data.SqlClient;
using System.Data;

namespace DemoA01ADO.DAC
{
	public static class NorthwindDAC
	{
		static string connection = "Server=data.cnt.sast.ca,24680;" +
									"Database=skelemen_Northwind;" +
									"User Id=skelemen;" +
									"Password=New_123;" +
									"Encrypt=False";

		public static List<List<string>> GetEmployees()
		{
			List<List<string>> returnedData = new List<List<string>>();

			using (SqlConnection conn = new SqlConnection(connection))
			{
				conn.Open();

				string query = "select * from Employees where FirstName like 'A%'";
				using (SqlCommand comm = new SqlCommand())
				{
					comm.CommandText = query;
					comm.Connection = conn;

					using(SqlDataReader reader = comm.ExecuteReader())
					{
						List<string> columnHeaders = new List<string>();
						for (int i = 0; i < reader.FieldCount; ++i)
							columnHeaders.Add(reader.GetName(i));
						returnedData.Add(columnHeaders);

						if (reader.HasRows)
							while(reader.Read())
							{
								List<string> row = new List<string>();
								for (int i = 0; i < reader.FieldCount; ++i)
									row.Add(reader[i]?.ToString());
								returnedData.Add(row);
							}
					}
				}
			}

			return returnedData;
		}

		public static List<string> GetProduct(int productID)
		{
			List<string> productInfo = new List<string>();

			using (SqlConnection conn = new SqlConnection(connection))
			{
				conn.Open();

				using (SqlCommand comm = new SqlCommand())
				{
					comm.CommandType = CommandType.StoredProcedure;
					comm.CommandText = "GetProduct";
					comm.Connection = conn;

					SqlParameter pID = new SqlParameter();
					pID.ParameterName = "@productID";
					pID.SqlDbType = SqlDbType.Int;
					pID.Direction = ParameterDirection.Input;
					pID.Value = productID;
					comm.Parameters.Add(pID);

					using (SqlDataReader reader = comm.ExecuteReader())
					{
						if (reader.HasRows)
						{
							reader.Read();
							for (int i = 0; i < reader.FieldCount; ++i)
								productInfo.Add(reader[i]?.ToString());	
						}
					}
				}
			}

			return productInfo;
		}
	}
}
