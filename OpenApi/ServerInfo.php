<?php declare (strict_types = 1);

/*
 * This file is part of the House Aratus package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="title",
 *     description="This is my api",
 *     termsOfService="https://www.housearatus.space",
 *     @OA\Contact(
 *         name="Sven Roodbol",
 *         email="roodbol.sven@gmail.com"
 *     ),
 *     @OA\License(
 *         name="(c) Sven Roodbol",
 *         url="https://www.housearatus.space"
 *     ),
 *     version="1.0.0"
 * )
 *
 * @OA\Get(
 *   path="/character/{id}",
 *   @OA\Response(response="200", description="OK response")
 * )
 *
 */
class ServerInfo
{

}
