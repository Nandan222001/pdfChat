<?php
function find_matches(array $chunks, array $keywords, int $crop = 500) {
    // Preprocess chunks to lowercase for case-insensitive matching
    $processedChunks = array_map('strtolower', $chunks);
    
    $df = [];
    foreach ($processedChunks as $chunk) {
        foreach ($keywords as $keyword) {
            $df[$keyword] = substr_count($chunk, $keyword);
        }
    }

    $results = [];
    foreach ($processedChunks as $chunk_id => $chunk) {
        foreach ($keywords as $keyword) {
            // Apply crop to chunk
            $croppedChunk = substr($chunk, $crop);
            $croppedLength = strlen($croppedChunk);
            if ($croppedLength > 0) {
                $croppedChunk .= ' ';
            }
            $croppedChunk .= substr($chunk, 0, -$crop);
            
            // Count occurrences of keyword in cropped chunk
            $occurrences = substr_count($croppedChunk, $keyword);
            
            // Calculate relevance score and store results
            $results[$chunk_id] = $occurrences / max(1, $df[$keyword]);
        }
    }

    arsort($results);
    return $results;
}
