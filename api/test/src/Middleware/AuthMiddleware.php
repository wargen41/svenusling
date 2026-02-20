<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');
        
        if (empty($authHeader)) {
            return $this->jsonResponse(
                ['error' => 'Missing authorization header'],
                401
            );
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);

        try {
            $decoded = JWT::decode(
                $token,
                new Key(JWT_SECRET, JWT_ALGORITHM)
            );
            
            // Attach user info to request
            $request = $request
                ->withAttribute('user_id', $decoded->sub)
                ->withAttribute('user_role', $decoded->role)
                ->withAttribute('user', $decoded);
                
        } catch (ExpiredException $e) {
            return $this->jsonResponse(['error' => 'Token expired'], 401);
        } catch (SignatureInvalidException $e) {
            return $this->jsonResponse(['error' => 'Invalid token signature'], 401);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => 'Invalid token'], 401);
        }

        return $handler->handle($request);
    }

    private function jsonResponse($data, $status = 200)
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode($data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}