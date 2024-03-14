<?php
require("ChatGPT.php");

// Define your OpenAI API key
//define('OPENAI_API_KEY', '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');
//gpt 4
define('OPENAI_API_KEY', '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');

/**
 * List keywords to the user
 *
 * @param array<string> $keywords A list of keywords
 */
function list_keywords( $keywords ) {

}
/**
 * Call this function if the answer is not found
 *
 * @param bool $not_found
 */
function answer_not_found( bool $not_found = true ) {

}
/**
 * Retrieve keywords from the given question.
 *
 * @param string $question The question for which keywords are to be generated.
 * @return array An array of keywords extracted from the question.
 */
function get_keywords(string $question): array {
    //$prompt = "I want to search for the answer to this question from a PDF file. Please give me a list of keywords that I could use to search for the information.\n```\n$question\n```\nUse the list_keywords function to respond.";
    $prompt = "Voglio cercare la risposta a questa domanda da un file PDF. Per favore, dammi una lista di parole chiave che potrei usare per cercare le informazioni.\n```\n$question\n```\nUsa la funzione list_keywords per rispondere.";

    $chatgpt = new ChatGPT(OPENAI_API_KEY);
    $chatgpt->add_function("list_keywords");
    //$chatgpt->smessage("You are a search keyword generator");
    $chatgpt->umessage($prompt);

    $response = $chatgpt->response(true);
    $function_call = $response->function_call;

    $arguments = json_decode($function_call->arguments, true);
    $keywords = strtolower(implode(" ", $arguments["keywords"]));
    $keywords = explode(" ", $keywords);

    return $keywords;
}

/**
 * Answer the given question based on the provided chunk from the PDF file.
 *
 * @param string $chunk The excerpt from the PDF file.
 * @param string $question The question to be answered.
 * @return mixed The response to the question.
 */
function answer_question(string $chunk, string $question) {
//    $chatgpt = new ChatGPT(OPENAI_API_KEY);
//    $chatgpt->add_function("answer_not_found");
//    $chatgpt->smessage("The user will give you an excerpt from PDF file. Answer the question based on the information in the excerpt. If the answer cannot be determined from the excerpt, call the answer_not_found function.");
//    $chatgpt->umessage("### EXCERPT FROM PDF:\n\n$chunk");
//    $chatgpt->umessage($question);
    
    $chatgpt = new ChatGPT(OPENAI_API_KEY);
    $chatgpt->add_function("answer_not_found");
    $chatgpt->smessage("L'utente ti fornirà un estratto da un file PDF. Rispondi alla domanda basandoti sulle informazioni nell'estratto. Se la risposta non può essere determinata dall'estratto, chiama la funzione answer_not_found.");
    $chatgpt->umessage("### ESTRATTO DAL PDF:\n\n$chunk");
    $chatgpt->umessage($question);

    $response = $chatgpt->response(true);

    if (isset($response->function_call) || empty($response->content)) {
        return false;
    }

    // Optionally, you can perform additional checks here before returning the response

    return $response;
}

/**
 * Check if the provided question and answer match certain criteria.
 *
 * @param string $question The question.
 * @param string $answer The answer.
 * @return bool Whether the question and answer match the criteria.
 */
function gpt3_check(string $question, string $answer): bool {
    $chatgpt = new ChatGPT(OPENAI_API_KEY);
//    $chatgpt->umessage("Question: \"$question\"\nAnswer: \"$answer\"\n\nAnswer YES if the answer is similar to 'the answer to the question was not found in the information provided' or 'the excerpt does not mention that'. Answer only YES or NO");
//
//    $response = $chatgpt->response();
//    return stripos($response->content, "yes") === false;
    
    // Translate the instruction into Italian
    $chatgpt->umessage("Domanda: \"$question\"\nRisposta: \"$answer\"\n\nRispondi SÌ se la risposta è simile a 'la risposta alla domanda non è stata trovata nelle informazioni fornite' o 'l'estratto non menziona ciò'. Rispondi solo SÌ o NO");

    $response = $chatgpt->response();
    // Check for "SÌ" in the response, considering the response is in Italian
    return stripos($response->content, "sì") === false;
}
?>
