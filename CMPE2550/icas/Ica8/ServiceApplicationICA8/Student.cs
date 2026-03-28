using Microsoft.Data.SqlClient;
using System.Data;
using System.Text.RegularExpressions;
namespace ServiceApplicationICA8
{
    public class Student
    {
        static string connection = "Server=data.cnt.sast.ca,24680;" +
                                   "Database=efmukamngadjou1_ClassTrak;" +
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

                string query = "select * from Students Where first_name like 'E%' or  first_name like 'F%' order by first_name ";
                using(SqlCommand comm = new SqlCommand(query, conn))
                {
                    using(SqlDataReader reader = comm.ExecuteReader())
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

        public static List<List<string>> GetStudentsInfo(int data)
        {
            Console.WriteLine(data);
            List<string> columnHeaders = new List<string>();
            List<List<string>> rowData = new List<List<string>>();

            using(SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

               //bool success=  int.TryParse(data, out int id);
                //Console.WriteLine(success);
                string query = $"Select c.class_id,  c.class_desc,  c.days,  c.start_date, c.instructor_id  , i.last_name,i.first_name from Classes c Join Instructors i on i.instructor_id = c.instructor_id Join class_to_student cs on c.class_id = cs.class_id Join Students s on cs.student_id = s.student_id where s.student_id = '{data}'";
                using(SqlCommand comm = new SqlCommand(query, conn))
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
    } 
}
