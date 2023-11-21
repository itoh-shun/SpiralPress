<?php
exec(
    'yarn install && npx tailwindcss -c ./tailwind.config.js -i resources/css/input.css -o resources/css/output.css.php --minify'
);

$dir = './resources/script/';
$js_files = glob($dir . "*.js");

// 2. 各 .js ファイルの内容を読み込む
$combined_content = "";
foreach ($js_files as $file) {
    $combined_content .= file_get_contents($file) . "\n";
}
$output_file = './resources/script/combined.php';
file_put_contents($output_file, $combined_content);
