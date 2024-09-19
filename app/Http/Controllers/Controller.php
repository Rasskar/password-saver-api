<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="API Documentation",
 *      version="1.0.0",
 *      description="Документация для API",
 *      @OA\Contact(
 *          email="alekscygankov20@gmail.com"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *      type="http",
 *      scheme="bearer",
 *      securityScheme="bearerAuth",
 * )
 *
 * @OA\Tag(
 *      name="Auth",
 *      description="Аутентификация"
 * )
 *
 * @OA\Tag(
 *      name="User",
 *      description="Пользователи"
 * )
 */
abstract class Controller
{
    //
}
