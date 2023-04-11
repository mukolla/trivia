<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Simple API application for experiment with PHP and Laravel",
 *    version="1.0.0",
 *     description="
 *     Materials:
 *     - https://developer.okta.com/blog/2018/10/23/php-laravel-angular-crud-app#why-use-okta-for-authentication
 *     - https://ivankolodiy.medium.com/how-to-write-swagger-documentation-for-laravel-api-tips-examples-5510fb392a94
 *     - https://ikolodiy.com/posts/laravel-swagger-tips-examples
 *     - https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose
 *     ",
 *
 *     @OA\Contact(
 *         email="mukolla@gmail.com"
 *     ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
