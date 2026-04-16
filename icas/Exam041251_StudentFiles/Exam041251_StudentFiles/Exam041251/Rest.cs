using Microsoft.Data.SqlClient;
using System.Diagnostics.Metrics;

namespace Exam041251
{
    public class Rest
    {
        static string connection = "Server=data.cnt.sast.ca,24680;" +
                                   "Database=efmukamngadjou1_RestaurantDB;" +
                                   "User Id=efmukamngadjou1;" +
                                   "Password=Rachel1980@,.;" +
                                   "Encrypt=False";


        public static List<List<string>> GetSummary(string stdate, string enddate)
        {


            List<string> columnHeaders = new List<string>();
            List<List<string>> rowData = new List<List<string>>();

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();
                
                string query = $"select distinct i.Itemid, i.ItemName, i.ItemPrice, sum(i.ItemPrice) as 'Total Revenue' from Items i join Orders o on o.itemid = i.itemid where  DATEDIFF(dd, '{stdate}', o.OrderDate) >= 0 and Datediff(dd, '{enddate}',o.OrderDate)<=0 group by i.Itemid,i.ItemName,i.ItemPrice";

                using (SqlCommand comm = new SqlCommand(query, conn))
                {
                    using (SqlDataReader reader = comm.ExecuteReader())
                    {
                        for (int i = 0; i < reader.FieldCount; i++)
                            columnHeaders.Add(reader.GetName(i));
                        rowData.Add(columnHeaders);
                        while (reader.Read())
                        {
                            List<string> row = new List<string>();
                            for (int i = 0; i < reader.FieldCount; i++)
                                row.Add(reader[i]?.ToString());
                            rowData.Add(row);
                        }
                    }
                }
                return rowData;
            }
        }

        public static string Additem(int  id, string name, double price)
        {
            if (name == null)
                return "The name must not be null";
            if (price < 0)
                return "the price must not be negative";
            try
            {
                using (SqlConnection conn = new SqlConnection(connection))
                {
                   
                    conn.Open();
                    string query1 = $"INSERT INTO efmukamngadjou1_RestaurantDB.dbo.Items ( Itemid,ItemName,ItemPrice) values ('{id}', '{name}', '{price}')";
                    using (SqlCommand comm = new SqlCommand(query1, conn))
                    {
                        if (id < 0 || id == null)
                            return "The id cannot be null";
                            comm.ExecuteNonQuery();

                    }
                }
                return "Item added successfully";
            }
            catch (SqlException ex)
            {
                return "The id was not found";
            }
            catch (Exception ex)
            {
                return "General Error: " + ex.Message;
            }
        }

        public static string Updateitem(int id, string name, double price)
        {
            if (name == null)
                return "The name must not be null";
            if (price < 0)
                return "the price must not be negative";
            
            try
            {
                using (SqlConnection conn = new SqlConnection(connection))
                {
                    conn.Open();
                    string query1 = $"UPDATE efmukamngadjou1_RestaurantDB.dbo.Items SET ItemName='{name}',ItemPrice={price} WHERE Itemid={id}";
                    using (SqlCommand comm = new SqlCommand(query1, conn))
                    {

                        comm.ExecuteNonQuery();
                    }

                }
                return "Item was successfully updated";
            }
            catch (SqlException ex)
            {
                return "SQL Error: " + ex.Message;
            }
        }

        public static string DeleteItem(int id)
        {
            try
            {
                using (SqlConnection conn = new SqlConnection(connection))
                {
                    conn.Open();
                    string query1 = "DELETE FROM efmukamngadjou1_RestaurantDB.dbo.Items WHERE Itemid = @id";


                    using (SqlCommand comm = new SqlCommand(query1, conn))
                    {
                        comm.Parameters.AddWithValue("@id", id);
                        comm.ExecuteNonQuery();
                    }

                }
                return "Item was successfully deleted";
            }
            catch (SqlException ex)
            {
                return "SQL Error: " + ex.Message;
            }
        }
    }
}
