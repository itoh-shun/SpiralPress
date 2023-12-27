
<?php
require_once "GlobalConfig.php";
require_once "Library/spiral-editorjs-php/autoload_static.php";
require_once (defined('BASE_PATH') ? BASE_PATH : "") . "framework/autoload_static.php";
require_once "SpiralPress/autoload_static.php";

use framework\Routing\Router;
use SpiralPress\App\Http\Controllers\CategoryController;
use SpiralPress\App\Http\Controllers\MediaController;
use SpiralPress\App\Http\Controllers\MenuController;
use SpiralPress\App\Http\Controllers\PostController;
use SpiralPress\App\Http\Controllers\ProjectController;
use SpiralPress\App\Http\Controllers\UrlFetchController;
use SpiralPress\App\Http\Controllers\Web\WelcomeController;
use SpiralPress\App\Http\Middreware\ProjectMiddleware;

/** */

/** components */

//param _method="" を指定すると GET PUT DELETE GET PATCH を区別できる

const VIEW_FILE_ROOT = "SpiralPress/resources";

/** sample */

Router::map("GET", "/", [WelcomeController:: class , "index"]);
Router::map("GET", "/header-edit", function(){
    echo view("html.header-edit");
});

Router::resource('projects', ProjectController::class);

Router::map("get", 'preview', [PostController::class, 'preview']);
Router::resource('media', MediaController::class);
//Router::map('POST','media/upload', [MediaController::class , 'upload'])->name('media.upload');
//Router::map('GET','media/url/:filename', [MediaController::class , 'url'])->name('media.url');

Router::map('POST','file/upload', [MediaController::class , 'fileUpload'])->name('file.upload');
Router::map('GET','file/url/:filename', [MediaController::class , 'fileUrl'])->name('file.url');

Router::map('GET','fetchUrl',[UrlFetchController::class , 'fetch'])->name('url.fetch');

Router::middlewares(ProjectMiddleware::class, function(){
    Router::prefix(":projectId",function(){
        Router::resource('posts', PostController::class);
        Router::resource('comments', CommentController::class);
        Router::resource('categories', CategoryController::class);
        Router::resource('menu', MenuController::class);
        Router::resource('settings', SettingController::class);
        Router::map('GET','/:categoryId/posts', [CategoryController::class , 'postsindex'])->name('categories.posts.index');
    });
});





$router = new Router();
//$router->middleware();毎回必ずチェックする場合はこっち
$app = new SpiralPress\SpiralPressApplication();
$exceptionHandler = new SpiralPress\App\Exceptions\ExceptionHandler();
$kernel = new \framework\Http\Kernel($app, $router ,$exceptionHandler);
$request = new \framework\Http\Request();

$kernel->handle($request);

