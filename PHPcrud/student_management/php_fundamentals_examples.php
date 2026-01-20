<?php
/**
 * PHP Fundamentals Examples
 * This file demonstrates all core PHP concepts covered in the presentation
 * Use this as a reference for learning PHP basics
 */

echo "<h1>PHP Fundamentals Examples</h1>";
echo "<hr>";

// ============================================
// 1. INTRODUCTION TO PHP
// ============================================
echo "<h2>1. Introduction to PHP</h2>";
echo "This is a PHP file. PHP code is enclosed in <?php ... ?> tags.<br>";
echo "Current PHP Version: " . phpversion() . "<br><br>";

// ============================================
// 2. VARIABLES & DATA TYPES
// ============================================
echo "<h2>2. Variables & Data Types</h2>";

// String
$name = "John Doe";
echo "String: \$name = '$name'<br>";

// Integer
$age = 25;
echo "Integer: \$age = $age<br>";

// Float
$height = 5.9;
echo "Float: \$height = $height<br>";

// Boolean
$isStudent = true;
echo "Boolean: \$isStudent = " . ($isStudent ? 'true' : 'false') . "<br>";

// Array (Indexed)
$courses = array("Math", "Science", "English");
echo "Array (Indexed): ";
print_r($courses);
echo "<br>";

// Array (Associative)
$student = array(
    "name" => "Jane Smith",
    "age" => 22,
    "email" => "jane@example.com"
);
echo "Array (Associative): ";
print_r($student);
echo "<br>";

// NULL
$address = null;
echo "NULL: \$address = " . ($address === null ? 'NULL' : 'not null') . "<br><br>";

// ============================================
// 3. OPERATORS
// ============================================
echo "<h2>3. Operators</h2>";

$a = 10;
$b = 3;

// Arithmetic
echo "Arithmetic Operators:<br>";
echo "$a + $b = " . ($a + $b) . " (Addition)<br>";
echo "$a - $b = " . ($a - $b) . " (Subtraction)<br>";
echo "$a * $b = " . ($a * $b) . " (Multiplication)<br>";
echo "$a / $b = " . ($a / $b) . " (Division)<br>";
echo "$a % $b = " . ($a % $b) . " (Modulus)<br><br>";

// Assignment
$x = 10;
echo "Assignment Operators:<br>";
echo "Initial: \$x = $x<br>";
$x += 5;
echo "After \$x += 5: \$x = $x<br>";

$text = "Hello";
$text .= " World";
echo "String Concatenation: \$text = '$text'<br><br>";

// Comparison
echo "Comparison Operators:<br>";
echo "10 == 10: " . (10 == 10 ? 'true' : 'false') . "<br>";
echo "10 === '10': " . (10 === '10' ? 'true' : 'false') . " (strict comparison)<br>";
echo "10 != 5: " . (10 != 5 ? 'true' : 'false') . "<br>";
echo "10 > 5: " . (10 > 5 ? 'true' : 'false') . "<br>";
echo "10 < 5: " . (10 < 5 ? 'true' : 'false') . "<br><br>";

// Logical
echo "Logical Operators:<br>";
$bool1 = true;
$bool2 = false;
echo "true && false: " . ($bool1 && $bool2 ? 'true' : 'false') . "<br>";
echo "true || false: " . ($bool1 || $bool2 ? 'true' : 'false') . "<br>";
echo "!true: " . (!$bool1 ? 'true' : 'false') . "<br><br>";

// ============================================
// 4. CONTROL STATEMENTS
// ============================================
echo "<h2>4. Control Statements</h2>";

// If-Else
$age = 20;
echo "If-Else Example (age = $age):<br>";
if ($age >= 18) {
    echo "You are an adult.<br>";
} elseif ($age >= 13) {
    echo "You are a teenager.<br>";
} else {
    echo "You are a child.<br>";
}
echo "<br>";

// Switch
$day = "Monday";
echo "Switch Example (day = $day):<br>";
switch ($day) {
    case "Monday":
        echo "Start of work week!<br>";
        break;
    case "Friday":
        echo "TGIF!<br>";
        break;
    case "Saturday":
    case "Sunday":
        echo "Weekend!<br>";
        break;
    default:
        echo "Regular day<br>";
}
echo "<br>";

// ============================================
// 5. LOOP STATEMENTS
// ============================================
echo "<h2>5. Loop Statements</h2>";

// For Loop
echo "For Loop (1 to 5):<br>";
for ($i = 1; $i <= 5; $i++) {
    echo "$i ";
}
echo "<br><br>";

// While Loop
echo "While Loop (1 to 5):<br>";
$count = 1;
while ($count <= 5) {
    echo "$count ";
    $count++;
}
echo "<br><br>";

// Foreach Loop
echo "Foreach Loop (Array traversal):<br>";
$fruits = array("Apple", "Banana", "Orange");
foreach ($fruits as $fruit) {
    echo "$fruit ";
}
echo "<br><br>";

// ============================================
// 6. ARRAYS
// ============================================
echo "<h2>6. Arrays</h2>";

// Indexed Array
echo "Indexed Array:<br>";
$colors = array("Red", "Green", "Blue");
echo "Colors: ";
foreach ($colors as $index => $color) {
    echo "[$index] => $color, ";
}
echo "<br>";
echo "First color: " . $colors[0] . "<br><br>";

// Associative Array
echo "Associative Array:<br>";
$student = array(
    "name" => "John Doe",
    "age" => 20,
    "email" => "john@example.com"
);
echo "Student Info:<br>";
foreach ($student as $key => $value) {
    echo "$key: $value<br>";
}
echo "<br>";

// ============================================
// 7. FUNCTIONS
// ============================================
echo "<h2>7. Functions</h2>";

// Custom Function
function greet($name) {
    return "Hello, " . $name . "!";
}

echo "Custom Function:<br>";
echo greet("John") . "<br>";
echo greet("Jane") . "<br><br>";

// Function with Multiple Parameters
function calculateSum($a, $b) {
    return $a + $b;
}

echo "Function with Parameters:<br>";
echo "calculateSum(5, 3) = " . calculateSum(5, 3) . "<br><br>";

// Built-in Functions
echo "Built-in PHP Functions:<br>";
$text = "hello world";
echo "Original: '$text'<br>";
echo "strtoupper(): " . strtoupper($text) . "<br>";
echo "strlen(): " . strlen($text) . "<br>";
echo "count([1,2,3]): " . count([1, 2, 3]) . "<br>";
echo "date('Y-m-d'): " . date('Y-m-d') . "<br><br>";

// ============================================
// 8. CLASSES (OOP)
// ============================================
echo "<h2>8. Classes (Introduction to OOP)</h2>";

// Simple Class
class Student {
    // Properties
    public $name;
    public $email;
    
    // Constructor
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }
    
    // Method
    public function displayInfo() {
        return "Name: " . $this->name . ", Email: " . $this->email;
    }
}

// Create Object
echo "OOP Example:<br>";
$student1 = new Student("John Doe", "john@example.com");
echo $student1->displayInfo() . "<br>";

$student2 = new Student("Jane Smith", "jane@example.com");
echo $student2->displayInfo() . "<br><br>";

// ============================================
// 9. FORM HANDLING (Example)
// ============================================
echo "<h2>9. Form Handling</h2>";
echo "This demonstrates how forms work in PHP:<br>";
echo "<form method='POST' action=''>";
echo "<input type='text' name='test_input' placeholder='Enter text'>";
echo "<button type='submit'>Submit</button>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['test_input'])) {
    $input = $_POST['test_input'];
    echo "<br>You submitted: " . htmlspecialchars($input) . "<br>";
    echo "Sanitized with trim(): '" . trim($input) . "'<br>";
}

echo "<br>";

// ============================================
// END
// ============================================
echo "<hr>";
echo "<p><strong>Note:</strong> This file demonstrates all PHP fundamentals covered in the presentation.</p>";
echo "<p>Open this file in your browser to see the output.</p>";
?>

