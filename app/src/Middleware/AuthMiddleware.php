<?php

namespace App\Middleware;

use App\Core\AppSettings;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Exceptions\HttpUnauthorizedException;

class AuthMiddleware implements MiddlewareInterface
{
    private string $jwtKey;

    public function __construct(string $jwtKey)
    {
        $this->jwtKey = $jwtKey;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            throw new HttpUnauthorizedException($request, "Missing or invalid Authorization header");
        }

        $token = trim(str_replace('Bearer', '', $authHeader));

        //echo "JWT KEY IN MIDDLEWARE" . $this->jwtKey;

        try {
            //decode
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
            //  var_dump($this->jwtKey);
            // dd($token);
            $request = $request->withAttribute('jwt', (array)$decoded);
            return $handler->handle($request);
        } catch (\Exception $e) {
            //anauthorized
            throw new HttpUnauthorizedException($request, "Invalid or expired token");
        }
    }
}
