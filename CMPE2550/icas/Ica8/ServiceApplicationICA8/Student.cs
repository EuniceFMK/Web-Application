using Microsoft.Data.SqlClient;
using System.Data;
namespace ServiceApplicationICA8
{
    public class Student
    {
        static string connection = "Server=data.cnt.sast.ca,24680;" +
                                   "Database=efmukamngadjou1_Northwind;" +
                                   "User Id=efmukamngadjou1;" +
                                   "Password=Rachel1980@,.;" +
                                   "Encrypt=False";
        public static List<List<string>> GetStudents()
        {
            List<string> columnHeaders = new List<string>();
            List<List<string>> rowData = new List<List<string>>();

            using(SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                string query = "select * from "
            }
        }
    }

  
}
