
<?php
require_once (defined('BASE_PATH') ? BASE_PATH : "") . "framework/autoload_static.php";
require_once "SpiralPress/autoload_static.php";

use framework\Routing\Router;
use SpiralPress\App\Http\Controllers\ProjectController;
use SpiralPress\App\Http\Controllers\Web\WelcomeController;

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

Router::prefix("/:projectId",function(){
    Router::resource('pages', PageController::class);
    Router::resource('posts', PostController::class);
    Router::resource('comments', CommentController::class);
    Router::resource('categories', CategoryController::class);
    Router::resource('menus', MenuController::class);
    Router::resource('settings', SettingController::class);
    Router::resource('media', MediaController::class);
});

$router = new Router();
//$router->middleware();毎回必ずチェックする場合はこっち
$app = new SpiralPress\SpiralPressApplication();
$exceptionHandler = new SpiralPress\App\Exceptions\ExceptionHandler();
$kernel = new \framework\Http\Kernel($app, $router ,$exceptionHandler);
$request = new \framework\Http\Request();

$kernel->handle($request);

