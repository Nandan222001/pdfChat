<?php
// Start or resume session with a unique session ID
session_id($_COOKIE['PHPSESSID'] ?? session_create_id());
session_start();

require("gpt.php");
require("lookup.php");
require("pdfread.php");

// Check if the OpenAI API key is set
if (!getenv("OPENAI_API_KEY")) {
    // Handle missing API key (optional)
}

// Retrieve the question from the POST request
$question = $_POST["question"];

// Define the filename of the PDF
//$filename = "Rendiconto_Sociale_2022.pdf";

// Function to store question and answer in session
function store_question_answer($question, $answer) {
    $_SESSION['question_answer'][] = array('question' => $question, 'answer' => $answer);
}
// Convert PDF to text
//$text_file = pdf_to_text($filename);
$text_file = "parsed_text.txt";

// Chunk the text file
$chunks = chunk_text_file($text_file);

// Get keywords from the question
$keywords = get_keywords($question);

// Find matches in the text chunks
$matches = find_matches($chunks, $keywords);

// Initialize response
$response = "I can't find the answer";

// Iterate through matches and find answer
foreach ($matches as $chunk_id => $points) {
    // Attempt to answer the question based on the chunk
    $answer = answer_question($chunks[$chunk_id], $question);

    // If an answer is found, update the response and break the loop
    if ($answer !== false) {
        $response = $answer->content;
         // Store the question and answer in the session
        store_question_answer($question, $response);
        break;
    }
}

// Output the response
echo $response;
?>
