<?php



exec(
    'yarn install && npx tailwindcss -c ./tailwind.config.js -i resources/css/input.css -o resources/css/bundle/output.css --minify'
);

exec(
    'yarn run webpack'
);

$sourceFile = './resources/script/bundle/bundle.js';
$chunkSize = 200 * 1024;  // 257KB
$partsContents = splitFile($sourceFile, $chunkSize);
// パート内容を各ファイルに保存
$partFiles = [];
$partNumber = 1;
$content = '<?php'.PHP_EOL;
foreach ($partsContents as $partContent) {
    $partFile = "./resources/script/bundle/bundle.part{$partNumber}.php";
    file_put_contents($partFile, $partContent);
    $content .= "require_once '"."SpiralPress/resources/script/bundle/bundle.part{$partNumber}.php"."';".PHP_EOL;
    $partFiles[] = $partFile;
    $partNumber++;
}

file_put_contents("./resources/script/bundle/bundle.php", $content);

$sourceFile = 'resources/css/bundle/output.css';  // 分割したいCSSファイルのパス
$chunkSize = 200 * 1024;  // 257KB
$partsContents = splitFile($sourceFile, $chunkSize);

// パート内容を各ファイルに保存
$partFiles = [];
$partNumber = 1;
$content = '<?php'.PHP_EOL;
foreach ($partsContents as $partContent) {
    $partFile = "{$sourceFile}.part{$partNumber}.php";
    file_put_contents($partFile, $partContent);
    $partFiles[] = $partFile;
    $content .= "require_once '"."SpiralPress/{$partFile}"."';".PHP_EOL;
    $partNumber++;
}
file_put_contents("./resources/css/bundle/bundle.php", $content);

function splitFile($sourceFile, $chunkSize) {
    $contents = file_get_contents($sourceFile);
    $parts = [];
    $partContent = '';
    $lines = explode("\n", $contents);
    
    foreach ($lines as $line) {
        $tempContent = $partContent . $line . "\n";
        if (strlen($tempContent) < $chunkSize) {
            $partContent = $tempContent;
        } else {
            // 現在の部分のサイズが制限に達した場合、その部分を保存する
            $parts[] = $partContent;
            $partContent = $line . "\n";  // 次の部分の開始
        }
    }

    // 最後の部分を追加
    if ($partContent !== '') {
        $parts[] = $partContent;
    }

    return $parts;
}
