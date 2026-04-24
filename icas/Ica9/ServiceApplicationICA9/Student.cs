using Microsoft.Data.SqlClient;
using System.Data;
using System.Security.Cryptography.Pkcs;
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

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                string query = "select * from Students Where first_name like 'E%' or  first_name like 'F%' order by first_name ";
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

        public static List<List<string>> GetStudentsInfo(int data)
        {
            Console.WriteLine(data);
            List<string> columnHeaders = new List<string>();
            List<List<string>> rowData = new List<List<string>>();

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                //bool success=  int.TryParse(data, out int id);
                //Console.WriteLine(success);
                string query = $"Select c.class_id,  c.class_desc,  c.days,  c.start_date, c.instructor_id  , i.last_name,i.first_name from Classes c Join Instructors i on i.instructor_id = c.instructor_id Join class_to_student cs on c.class_id = cs.class_id Join Students s on cs.student_id = s.student_id where s.student_id = '{data}'";
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
        public static void DeleteStudent(int id)
        {
            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();
                string query1 = "DELETE FROM class_to_student WHERE student_id = @id";
                string query2 = "DELETE FROM Results WHERE student_id = @id";
                string query = "DELETE FROM Students WHERE student_id = @id";

                using (SqlCommand comm = new SqlCommand(query1, conn))
                {
                    comm.Parameters.AddWithValue("@id", id);
                    comm.ExecuteNonQuery();
                }
                using (SqlCommand comm3 = new SqlCommand(query2, conn))
                {
                    comm3.Parameters.AddWithValue("@id", id);
                    comm3.ExecuteNonQuery();
                }
                using (SqlCommand comm2 = new SqlCommand(query, conn))
                {
                    comm2.Parameters.AddWithValue("@id", id);
                    comm2.ExecuteNonQuery();
                }
            }
        }

//         return new {
//             success = totalRows > 0,
//     rows = totalRows,
//     message = totalRows > 0 ? "Deleted successfully" : "Student not found"
// };
        public static string EditStudent(int id, string fname, string lname, int shid)
        {
            try
            {


                using (SqlConnection conn = new SqlConnection(connection))
                {
                    conn.Open();
                    string query1 = $"UPDATE Students SET last_name='{lname}', first_name='{fname}', school_id='{shid}'  WHERE student_id = {id}";
                    using (SqlCommand comm = new SqlCommand(query1, conn))
                    {

                        if (shid < 0)
                            return "The school id canno be negative";

                        comm.ExecuteNonQuery();
                    }

                }
                return "Student was successfully edited";
            }
            catch (SqlException ex)
            {
                return "SQL Error: " + ex.Message;
            }
        }

        public static List<List<string>> GetClassID()
        {
            List<string> columnHeaders = new List<string>();
            List<List<string>> rowData = new List<List<string>>();

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                //bool success=  int.TryParse(data, out int id);
                //Console.WriteLine(success);
                string query = $"Select c.class_desc from Classes c ";
                using (SqlCommand comm = new SqlCommand(query, conn))
                {
                    using (SqlDataReader reader = comm.ExecuteReader())
                    {
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

        public static string AddStudent(string fname, string lname, int shid, string classid)
        {
            try
            {
                using (SqlConnection conn = new SqlConnection(connection))
                {
                    conn.Open();
                   
                    string query1 = $"INSERT INTO Students ( last_name,first_name,school_id) values ('{lname}', '{fname}', '{shid}')";
                    using (SqlCommand comm = new SqlCommand(query1, conn))
                    {

                        if (string.IsNullOrWhiteSpace(lname))
                            return "The last name cannot be null";
                        else if (string.IsNullOrWhiteSpace(fname))
                            return "The fisrt name cannot be null";
                        else if (shid < 0)
                            return "The school id cannot be negative";
                        comm.ExecuteNonQuery();

                    }
                }
                return "Student added successfully";
            }
            catch (SqlException ex)
            {
                return "SQL Error: " + ex.Message;
            }
            catch (Exception ex)
            {
                return "General Error: " + ex.Message;
            }
        }
    }
}
