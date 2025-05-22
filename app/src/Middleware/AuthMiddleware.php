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
use PhpDocReader\PhpParser\TokenParser;

/**
 * Middleware responsible for authenticating incoming request using JWT
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * The secret key used to validate JWTs
     * @var string
     */
    private string $jwtKey;

    public function __construct(string $jwtKey)
    {
        $this->jwtKey = $jwtKey;
    }
    /**
     * Intercepts the incoming request, validate the JWT and attaches the decode data
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @throws \App\Exceptions\HttpUnauthorizedException
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $uri = $request->getUri()->getPath();
        // retrieve the authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        //valudate header format
        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            throw new HttpUnauthorizedException($request, "Missing or invalid Authorization header");
        }
        //Extract the JWT token from the authorization header
        $token = trim(str_replace('Bearer', '', $authHeader));

        //dd("meow");


        try {
            //decode the token using the HS256 algoritm and provide secret key
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));

            $decodedArray = (array)$decoded;

            // Store decoded info in $_SESSION
            $_SESSION['user'] = [
                'id' => $decodedArray['id'] ?? null,
                'email'   => $decodedArray['email'] ?? null,
            ];

            //Attach decode JWT to the request attributes
            $request = $request->withAttribute('jwt', (array)$decoded);

            //Proceed to the next step (either another middleware or controller)
            return $handler->handle($request);
        } catch (\Exception $e) {
            //unauthorized
            error_log('AuthMiddleware threw: ' . $e->getMessage());
            throw $e;
            //    throw new HttpUnauthorizedException($request, "Invalid or expired token");
        }
    }
}
