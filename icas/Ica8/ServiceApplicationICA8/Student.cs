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

        public static List<List<string>> GetStudentsByLetter(string letter)
        {
            // Liste finale à retourner
            List<List<string>> rowData = new List<List<string>>();

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                // Requête paramétrée
                string query = @"SELECT * FROM Students 
                         WHERE first_name LIKE @prefix
                         ORDER BY first_name";

                using (SqlCommand comm = new SqlCommand(query, conn))
                {
                    // Création du paramètre (version propre comme ton premier exemple)
                    SqlParameter param = new SqlParameter();
                    param.ParameterName = "@prefix";
                    param.SqlDbType = System.Data.SqlDbType.NVarChar;
                    param.Size = 50;
                    param.Value = letter + "%"; // ex: "E%"

                    comm.Parameters.Add(param);

                    using (SqlDataReader reader = comm.ExecuteReader())
                    {
                        // 🔹 Récupérer les noms des colonnes
                        List<string> headers = new List<string>();
                        for (int i = 0; i < reader.FieldCount; i++)
                        {
                            headers.Add(reader.GetName(i));
                        }
                        rowData.Add(headers);

                        // 🔹 Récupérer les lignes
                        while (reader.Read())
                        {
                            List<string> row = new List<string>();

                            for (int i = 0; i < reader.FieldCount; i++)
                            {
                                row.Add(reader[i]?.ToString());
                            }

                            rowData.Add(row);
                        }
                    }
                }
            }

            return rowData;
        }

        public static List<List<string>> GetStudentsByLetters(List<string> letters)
        {
            List<List<string>> rowData = new List<List<string>>();

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                // 🔹 Construire dynamiquement la condition WHERE
                List<string> conditions = new List<string>();

                for (int i = 0; i < letters.Count; i++)
                {
                    conditions.Add($"first_name LIKE @p{i}");
                }

                string whereClause = string.Join(" OR ", conditions);

                string query = $@"SELECT * FROM Students 
                          WHERE {whereClause}
                          ORDER BY first_name";

                using (SqlCommand comm = new SqlCommand(query, conn))
                {
                    // 🔹 Ajouter les paramètres
                    for (int i = 0; i < letters.Count; i++)
                    {
                        SqlParameter param = new SqlParameter();
                        param.ParameterName = $"@p{i}";
                        param.SqlDbType = System.Data.SqlDbType.NVarChar;
                        param.Size = 50;
                        param.Value = letters[i] + "%";

                        comm.Parameters.Add(param);
                    }

                    using (SqlDataReader reader = comm.ExecuteReader())
                    {
                        // 🔹 Headers
                        List<string> headers = new List<string>();
                        for (int i = 0; i < reader.FieldCount; i++)
                            headers.Add(reader.GetName(i));

                        rowData.Add(headers);

                        // 🔹 Rows
                        while (reader.Read())
                        {
                            List<string> row = new List<string>();

                            for (int i = 0; i < reader.FieldCount; i++)
                                row.Add(reader[i]?.ToString());

                            rowData.Add(row);
                        }
                    }
                }
            }

            return rowData;
        }

        var letters = new List<string> { "E", "F", "G" };

        var students = GetStudentsByLetters(letters);
        public static string GetAverage(int startId, int endId)
        {
            int sumMin = 0;
            int sumMax = 0;
            int count = 0;

            using (SqlConnection conn = new SqlConnection(connection))
            {
                conn.Open();

                string query = "SELECT tagMin, tagMax FROM TAGS WHERE tagID BETWEEN @start AND @end";

                using (SqlCommand comm = new SqlCommand(query, conn))
                {
                    comm.Parameters.AddWithValue("@start", startId);
                    comm.Parameters.AddWithValue("@end", endId);

                    using (SqlDataReader reader = comm.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            sumMin += Convert.ToInt32(reader["tagMin"]);
                            sumMax += Convert.ToInt32(reader["tagMax"]);
                            count++;
                        }
                    }
                }
            }

            if (count == 0)
                return "No data in that range";

            double avgMin = (double)sumMin / count;
            double avgMax = (double)sumMax / count;

            return $"Average Min: {avgMin}, Average Max: {avgMax}";
        }
    }
}
