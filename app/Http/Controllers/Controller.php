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
 *     - https://techsolutionstuff.com/post/laravel-8-socialite-login-with-google-account
 *     - https://codyrigg.medium.com/how-to-add-a-google-login-using-socialite-on-laravel-8-with-jetstream-6153581e7dc9
 *     - https://blog.ithillel.ua/articles/oauth-2-0-autentifikaciya-cerez-google-yak-realizuvati-vxid-cerez-google-na-saiti
 *     - https://laravel.su/docs/8.x/authentication#starter-kits
 *     - https://laravel.su/docs/8.x/authorization
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
