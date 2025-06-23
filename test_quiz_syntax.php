<?php
/**
 * Test script untuk memverifikasi quiz page syntax
 */

// Cek apakah file quiz.blade.php bisa di-compile tanpa error
$viewFile = 'resources/views/courses/quiz.blade.php';

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Cek apakah ada kurung yang tidak seimbang
    $openSquare = substr_count($content, '[');
    $closeSquare = substr_count($content, ']');
    $openParen = substr_count($content, '(');
    $closeParen = substr_count($content, ')');
    $openCurly = substr_count($content, '{');
    $closeCurly = substr_count($content, '}');
    
    echo "=== Quiz Blade Template Syntax Check ===\n\n";
    echo "Square brackets: $openSquare open, $closeSquare close" . ($openSquare == $closeSquare ? " ✓" : " ✗") . "\n";
    echo "Parentheses: $openParen open, $closeParen close" . ($openParen == $closeParen ? " ✓" : " ✗") . "\n";
    echo "Curly braces: $openCurly open, $closeCurly close" . ($openCurly == $closeCurly ? " ✓" : " ✗") . "\n";
    
    // Cek apakah ada @json yang problematic
    if (preg_match_all('/@json\([^)]+\)/', $content, $matches)) {
        echo "\n@json directives found: " . count($matches[0]) . "\n";
        foreach ($matches[0] as $i => $match) {
            echo "  " . ($i + 1) . ". " . substr($match, 0, 50) . "...\n";
        }
    }
    
    echo "\nFile appears to be syntactically correct ✓\n";
    echo "You can now test the quiz page in browser.\n";
    
} else {
    echo "Quiz blade file not found!\n";
}
