# 💻 Web Technology Lab Exam Cheat Sheet (SELECT Only)

## 🔁 Core Flow (Memorize This)

JS (AJAX) → service.php → Clean inputs → Check action → SELECT query →
$output array → json_encode($output) → JS updates DOM

------------------------------------------------------------------------

## 📌 jQuery Document Ready

``` javascript
$(document).ready(function () {
    CallAjax(
        "service.php",
        "GET",
        { action: "GetAllDepartments" },
        "json",
        loadDepartments,
        ajaxError
    );
});
```

------------------------------------------------------------------------

## 📌 Generic AJAX Function

``` javascript
function CallAjax(url, method, data, dataType, successCallback, errorCallback) {
    let ajaxOptions = {};
    ajaxOptions["url"] = url;
    ajaxOptions["method"] = method;
    ajaxOptions["data"] = data;
    ajaxOptions["dataType"] = dataType;
    ajaxOptions["success"] = successCallback;
    ajaxOptions["error"] = errorCallback;

    return $.ajax(ajaxOptions);
}
```

------------------------------------------------------------------------

## 📌 Loading Data into a Table

``` javascript
function loadDepartments(response) {
    let tbody = $("#deptBody");
    tbody.empty();

    if (!response.departments || response.departments.length == 0) {
        tbody.append("<tr><td colspan='3'>No records found</td></tr>");
        return;
    }

    response.departments.forEach(row => {
        tbody.append(`
            <tr>
                <td><button onclick="getEmployees('${row[0]}')">View</button></td>
                <td>${row[0]}</td>
                <td>${row[1]}</td>
            </tr>
        `);
    });

    tbody.append(`Retrieved: ${response.departments.length} records`);
}
```

------------------------------------------------------------------------

## 📌 Click Event → Load Related Data

``` javascript
function getEmployees(dept_id) {
    CallAjax(
        "service.php",
        "GET",
        {
            action: "GetEmployeesByDept",
            dept_id: dept_id
        },
        "json",
        loadEmployees,
        ajaxError
    );
}
```

------------------------------------------------------------------------

## 📌 PHP service.php Template

``` php
<?php
require_once "db.php";

$output = [];
$clean = [];

// Clean GET input
foreach ($_GET as $key => $value) {
    $clean[$connection->real_escape_string($key)] =
        $connection->real_escape_string($value);
}

// Route actions
if (isset($clean["action"])) {

    if ($clean["action"] == "GetAllDepartments")
        GetAllDepartments();

    if ($clean["action"] == "GetEmployeesByDept" && isset($clean["dept_id"]))
        GetEmployeesByDept($clean["dept_id"]);
}

echo json_encode($output);
die();
?>
```

------------------------------------------------------------------------

## 📌 SELECT Function (Simple)

``` php
function GetAllDepartments() {
    global $output;

    $query = "SELECT dept_id, dept_name FROM departments";
    $result = mySqlQuery($query);

    if ($result)
        $output["departments"] = $result->fetch_all();
    else
        $output["departments"] = [];
}
```

------------------------------------------------------------------------

## 📌 SELECT with JOIN

``` php
function GetEmployeesByDept($dept_id) {
    global $output;

    $query = "
        SELECT emp_id, emp_name, salary
        FROM employees
        WHERE dept_id = '$dept_id'
    ";

    $result = mySqlQuery($query);

    if ($result && $result->num_rows > 0)
        $output["employees"] = $result->fetch_all();
    else
        $output["employees"] = [];
}
```

------------------------------------------------------------------------

## 🚨 Always Remember

-   Always use `tbody.empty()`
-   Always check `if (!response.xxx || response.xxx.length == 0)`
-   Always return JSON with `json_encode($output);`
-   Always validate `isset($clean["param"])`
-   No INSERT / UPDATE / DELETE (SELECT only)

------------------------------------------------------------------------

## 🧠 Common Exam Mistakes

-   Forgetting `dataType: "json"`
-   Forgetting `json_encode()`
-   Using wrong action name
-   Not checking empty results
-   Forgetting `die();` after JSON output

------------------------------------------------------------------------

🔥 If you can build this pattern from memory, you're exam-ready.
