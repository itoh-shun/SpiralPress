
<?php

use App\Models\Media;
use framework\Exception\NotFoundException;
require_once "GlobalConfig.php";
require_once "Library/spiral-editorjs-php/autoload_static.php";
require_once (defined('BASE_PATH') ? BASE_PATH : "") . "framework/autoload_static.php";
require_once "SpiralPress/autoload_static.php";

use framework\Routing\Router;
use SpiralPress\App\Http\Controllers\Site\SiteController;

/** */
if(!defined('PROJECTID')){
    throw new NotFoundException();
}

define('PROJECTID', PROJECTID);

/** components */
const VIEW_FILE_ROOT = "SpiralPress/resources";

Router::map("GET", "/", [SiteController:: class , "index"]);

Router::map("GET", "/:postShareId", [SiteController:: class , "show"])->name('post.show');

Router::map("GET", 'file/:fileName' , function($vars){
    $media = Media::instance()->where('fileName', $vars['fileName'])->get()->first();
    echo '<meta http-equiv="refresh" content="0;URL='.$media->file.'">';
});

$router = new Router();
//$router->middleware();毎回必ずチェックする場合はこっち
$app = new SpiralPress\SpiralPressSiteApplication();
$exceptionHandler = new SpiralPress\App\Exceptions\ExceptionHandler();
$kernel = new \framework\Http\Kernel($app, $router ,$exceptionHandler);
$request = new \framework\Http\Request();

$kernel->handle($request);

