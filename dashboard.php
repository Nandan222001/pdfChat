<?php
// Start or resume the session
session_start();

// Check if there are stored questions and answers in the session
if (isset($_SESSION['question_answer']) && !empty($_SESSION['question_answer'])) {
    echo "<h1>Dashboard</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Question</th><th>Answer</th></tr>";
    
    // Iterate through stored questions and answers and display them in a table
    foreach ($_SESSION['question_answer'] as $qa) {
        echo "<tr><td>{$qa['question']}</td><td>{$qa['answer']}</td></tr>";
    }
    
    echo "</table>";
} else {
    echo "<h1>No questions and answers found</h1>";
}
?>
