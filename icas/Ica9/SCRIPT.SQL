use efmukamngadjou1_ClassTrak
go
select * from Students Where first_name like 'E%' or  first_name like 'F%' order by first_name 
Select c.class_id,  c.class_desc,  c.days,  c.start_date, c.instructor_id  , i.last_name,i.first_name
from Classes c 
Join Instructors i on i.instructor_id = c.instructor_id
Join class_to_student cs on c.class_id = cs.class_id  
Join Students s on cs.student_id = s.student_id 
where s.student_id = '677';

