<?php
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

function pdf_to_text( $filename ) {
    $pdfText = extractTextFromPDF($filename);
    // Save parsed text into a text file
    file_put_contents('parsed_text.txt', $pdfText);
    
    $temp_file = "parsed_text.txt";
    return $temp_file;
}
function extractTextFromPDF($pdfFile) {
    $parser = new Parser();
    $pdf = $parser->parseFile($pdfFile);
    $text = $pdf->getText();
    return $text;
}
//old
    //function pdf_to_text( $filename ) {
    //    $temp_file = tempnam( "/tmp", "PDFTOTEXT" );
    //    exec( "pdftotext " . escapeshellarg( $filename ) . " " . escapeshellarg( $temp_file ), $output, $return_var );
    //
    //    if ($return_var !== 0) {
    //        throw new \Exception("Error executing pdftotext command");
    //    }
    //
    //    if (!file_exists($temp_file)) {
    //        throw new \Exception("Temp file not created");
    //    }
    //
    //    register_shutdown_function(function() use ( $temp_file ) {
    //        @unlink($temp_file);
    //    });
    //
    //    return $temp_file;
    //}

function chunk_text_file( $filename, $chunk_size = 4000, $overlap = 1000 ) {
    if( $overlap > $chunk_size ) {
        throw new \Exception( "Overlap must be smaller than chunk size" );
    }

    $chunks = [];

    $file = fopen( $filename, "r" );
    if (!$file) {
        throw new \Exception("Error opening file");
    }

    while( ! feof( $file ) ) {
        $chunk = fread( $file, $chunk_size );
        $chunks[] = $chunk;
        if( feof( $file ) ) {
            break;
        }
        fseek( $file, -$overlap, SEEK_CUR );
    }

    fclose($file);

    return $chunks;
}
