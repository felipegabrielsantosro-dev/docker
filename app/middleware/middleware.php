<?php

namespace app\middleware;

class Middleware
{
    public static function authentication()
    {
        #Retorna um closure (função anônima)
        $middleware = function ($request, $handler) {
           
            // Lógica de autenticação
            $authenticated = true; // Simulação de autenticação bem-sucedida

            if ($authenticated) {
                // Prosseguir para o próximo middleware ou controlador
                return $handler->handle($request);
            } else {
                // Retornar resposta de erro de autenticação
                $response = new \Slim\Psr7\Response(401);
                $response->getBody()->write('Unauthorized');
                return $response;
            }
        };
        return $middleware;
    }
}