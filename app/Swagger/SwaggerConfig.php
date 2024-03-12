<?php

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Product API",
 *         description="This is a sample server for a product API.",
 *         termsOfService="http://example.com/terms/",
 *         @OA\Contact(
 *             email="support@example.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @OA\Server(
 *         description="API Server",
 *         url="/api"
 *     ),
 * )
 */
class SwaggerConfig
{
    // You don't actually need to put any PHP code in this class.
    // It's just a convenient place to put your Swagger annotations.
}
