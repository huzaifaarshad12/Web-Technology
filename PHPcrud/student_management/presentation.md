# PHP Fundamentals & CRUD Application Implementation
## Presentation Deck

---

## Slide 1: Introduction to PHP
### What is PHP?
- **PHP** (Hypertext Preprocessor) is a server-side scripting language
- Designed for web development
- Executed on the server before sending HTML to the browser
- Can generate dynamic web page content
- Widely used with databases (MySQL, PostgreSQL, etc.)

### Basic PHP Syntax
```php
<?php
// PHP code goes here
echo "Hello, World!";
?>
```

**Key Points:**
- PHP code is enclosed in `<?php ... ?>` tags
- Can be embedded in HTML files
- File extension: `.php`

---

## Slide 2: Variables & Data Types
### Variable Declaration
- Variables in PHP start with the **$** symbol
- No need to declare data type (loosely typed)
- Case-sensitive variable names

### Example:
```php
<?php
$name = "John Doe";      // String
$age = 25;               // Integer
$height = 5.9;           // Float
$isStudent = true;       // Boolean
$courses = array("Math", "Science"); // Array
$address = null;         // NULL
?>
```

### Data Types:
1. **String**: Text data (e.g., `"Hello"`)
2. **Integer**: Whole numbers (e.g., `42`)
3. **Float**: Decimal numbers (e.g., `3.14`)
4. **Boolean**: True or false (e.g., `true`, `false`)
5. **Array**: Collection of values
6. **NULL**: Represents empty variable

---

## Slide 3: Operators
### Arithmetic Operators
```php
<?php
$a = 10;
$b = 3;

echo $a + $b;  // 13 (Addition)
echo $a - $b;  // 7  (Subtraction)
echo $a * $b;  // 30 (Multiplication)
echo $a / $b;  // 3.333... (Division)
echo $a % $b;  // 1  (Modulus - remainder)
?>
```

### Assignment Operators
```php
<?php
$x = 10;      // Basic assignment
$x += 5;      // $x = $x + 5 (result: 15)
$x -= 3;      // $x = $x - 3 (result: 12)

$text = "Hello";
$text .= " World";  // String concatenation (result: "Hello World")
?>
```

### Comparison Operators
```php
<?php
$a == $b;   // Equal (value)
$a === $b;  // Identical (value and type)
$a != $b;   // Not equal
$a !== $b;  // Not identical
$a > $b;    // Greater than
$a < $b;    // Less than
$a >= $b;   // Greater than or equal
$a <= $b;   // Less than or equal
?>
```

### Logical Operators
```php
<?php
$a && $b;   // AND - both true
$a || $b;   // OR - at least one true
!$a;        // NOT - reverse boolean
?>
```

---

## Slide 4: Control Statements
### If-Else Statements
```php
<?php
$age = 20;

if ($age >= 18) {
    echo "You are an adult.";
} elseif ($age >= 13) {
    echo "You are a teenager.";
} else {
    echo "You are a child.";
}
?>
```

### Switch Statement
```php
<?php
$day = "Monday";

switch ($day) {
    case "Monday":
        echo "Start of work week!";
        break;
    case "Friday":
        echo "TGIF!";
        break;
    case "Saturday":
    case "Sunday":
        echo "Weekend!";
        break;
    default:
        echo "Regular day";
}
?>
```

---

## Slide 5: Loop Statements
### For Loop
```php
<?php
// Print numbers from 1 to 5
for ($i = 1; $i <= 5; $i++) {
    echo $i . " ";
}
// Output: 1 2 3 4 5
?>
```

### While Loop
```php
<?php
$count = 1;
while ($count <= 5) {
    echo $count . " ";
    $count++;
}
// Output: 1 2 3 4 5
?>
```

### Foreach Loop
```php
<?php
$fruits = array("Apple", "Banana", "Orange");

foreach ($fruits as $fruit) {
    echo $fruit . " ";
}
// Output: Apple Banana Orange
?>
```

---

## Slide 6: Arrays
### Indexed Arrays
```php
<?php
// Create indexed array
$colors = array("Red", "Green", "Blue");
// Or using short syntax
$colors = ["Red", "Green", "Blue"];

// Access elements
echo $colors[0];  // Output: Red
echo $colors[1];  // Output: Green
?>
```

### Associative Arrays
```php
<?php
// Create associative array (key-value pairs)
$student = array(
    "name" => "John Doe",
    "age" => 20,
    "email" => "john@example.com"
);

// Access elements
echo $student["name"];   // Output: John Doe
echo $student["age"];    // Output: 20
?>
```

### Traversing Arrays with Foreach
```php
<?php
// Indexed array
$fruits = ["Apple", "Banana", "Orange"];
foreach ($fruits as $index => $fruit) {
    echo "$index: $fruit<br>";
}

// Associative array
$student = ["name" => "John", "age" => 20];
foreach ($student as $key => $value) {
    echo "$key: $value<br>";
}
?>
```

---

## Slide 7: Functions
### Why Use Functions?
- **Code Reusability**: Write once, use many times
- **Organization**: Break code into manageable pieces
- **Maintainability**: Easier to update and debug

### Custom Functions
```php
<?php
// Define a function
function greet($name) {
    return "Hello, " . $name . "!";
}

// Call the function
echo greet("John");  // Output: Hello, John!
?>
```

### Function with Multiple Parameters
```php
<?php
function calculateSum($a, $b) {
    return $a + $b;
}

echo calculateSum(5, 3);  // Output: 8
?>
```

### Built-in PHP Functions
```php
<?php
$text = "hello world";
echo strtoupper($text);     // HELLO WORLD
echo strlen($text);          // 11
echo count([1, 2, 3]);       // 3
echo date("Y-m-d");          // Current date
?>
```

---

## Slide 8: Classes (Introduction to OOP)
### Object-Oriented Programming (OOP)
- **Class**: Blueprint for creating objects
- **Object**: Instance of a class
- **Properties**: Variables in a class
- **Methods**: Functions in a class

### Simple Class Example
```php
<?php
class Student {
    // Properties (variables)
    public $name;
    public $email;
    
    // Method (function)
    public function displayInfo() {
        return "Name: " . $this->name . ", Email: " . $this->email;
    }
}

// Create object (instance)
$student1 = new Student();
$student1->name = "John Doe";
$student1->email = "john@example.com";

// Call method
echo $student1->displayInfo();
?>
```

### Constructor Method
```php
<?php
class Student {
    public $name;
    public $email;
    
    // Constructor - runs when object is created
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }
    
    public function displayInfo() {
        return "Name: " . $this->name . ", Email: " . $this->email;
    }
}

// Create object with constructor
$student1 = new Student("John Doe", "john@example.com");
echo $student1->displayInfo();
?>
```

---

## Slide 9: Database Connectivity
### MySQL Database Connection
PHP provides two main methods:
1. **MySQLi** (MySQL Improved)
2. **PDO** (PHP Data Objects)

### MySQLi Connection Example
```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "student_management";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>
```

### Key Points:
- Always check for connection errors
- Use prepared statements to prevent SQL injection
- Close connections when done

---

## Slide 10: CRUD Operations Overview
### What is CRUD?
- **C**reate: Insert new records
- **R**ead: Retrieve and display records
- **U**pdate: Modify existing records
- **D**elete: Remove records

### Our Student Management System
- **Create**: Form to add new students
- **Read**: Display all students in a table
- **Update**: Edit student information
- **Delete**: Remove student records

---

## Slide 11: Form Handling & Security
### Capturing Form Data
```php
<?php
// Using $_POST (for POST method)
$name = $_POST['name'];
$email = $_POST['email'];

// Using $_GET (for GET method)
$id = $_GET['id'];
?>
```

### Input Sanitization & Validation
```php
<?php
// Sanitize input
$name = trim($_POST['name']);           // Remove whitespace
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

// Validate input
if (empty($name)) {
    echo "Name is required!";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format!";
}
?>
```

### Security Best Practices
1. **Always validate** user input
2. **Sanitize** data before storing
3. **Use prepared statements** to prevent SQL injection
4. **Escape output** using `htmlspecialchars()` to prevent XSS attacks

---

## Slide 12: Prepared Statements (SQL Injection Prevention)
### Why Prepared Statements?
- **Security**: Prevents SQL injection attacks
- **Performance**: Faster execution for repeated queries
- **Reliability**: Handles special characters automatically

### Example: Insert with Prepared Statement
```php
<?php
$stmt = $conn->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);  // "ss" = two strings
$stmt->execute();
$stmt->close();
?>
```

### Example: Select with Prepared Statement
```php
<?php
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);  // "i" = integer
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
?>
```

---

## Slide 13: Live Demo - CRUD Application
### Features Demonstrated:
1. ✅ **Create**: Add new student via form
2. ✅ **Read**: Display all students in table
3. ✅ **Update**: Edit existing student details
4. ✅ **Delete**: Remove student with confirmation

### Key Implementation Points:
- Clean, user-friendly interface
- Form validation and error handling
- Secure database operations
- Responsive design

---

## Slide 14: Summary
### What We Covered:
1. ✅ PHP syntax and basics
2. ✅ Variables, data types, and operators
3. ✅ Control structures and loops
4. ✅ Arrays and functions
5. ✅ Introduction to OOP (classes)
6. ✅ Database connectivity
7. ✅ Complete CRUD application

### Key Takeaways:
- PHP is powerful for server-side web development
- Security is crucial (prepared statements, input validation)
- CRUD operations are fundamental to web applications
- Practice makes perfect!

---

## Slide 15: Thank You & Questions
### Resources:
- PHP Official Documentation: https://www.php.net/docs.php
- MySQL Documentation: https://dev.mysql.com/doc/
- Practice: Build your own CRUD applications!

### Questions?
Feel free to ask about:
- PHP concepts
- Database operations
- Security best practices
- Application architecture

---

**End of Presentation**

