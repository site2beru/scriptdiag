<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// делаем поиск кода в файлах сайта
function findInFiles($directory, $searchString) {
    $found = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'html', 'tpl'])) { // в каких типах файлов ищем
            $content = file_get_contents($file->getPathname());
            if (strpos($content, $searchString) !== false) {
                $found[] = $file->getPathname();
            }
        }
    }
    return $found;
}

// ищем проблемные куски кода
$searchStrings = [
    '.main_menu' // что ищем. например: <style>, .class, function и т.п. 
];

echo "<h2>Поиск источника проблемного скрипта</h2>";

foreach ($searchStrings as $search) {
    $results = findInFiles($_SERVER['DOCUMENT_ROOT'] . '/netcat_template', $search); // где ищем. например, в папке с шаблонами
    if (!empty($results)) {
        echo "<h3>Найдено '$search':</h3>";
        foreach ($results as $file) {
            echo "<p>" . htmlspecialchars($file) . "</p>";
        }
    }
}
