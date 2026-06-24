<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoHtmlCache
{
    /**
     * Impede o cache das páginas HTML (navegador, proxy e LiteSpeed/HostGator),
     * garantindo que toda atualização do sistema chegue aos usuários sem precisar
     * limpar cache manualmente. Os assets (CSS/JS com hash no nome via Vite) são
     * servidos como arquivos estáticos e mantêm seu próprio cache longo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Só mexe em respostas HTML — não afeta PDF, downloads, JSON ou assets.
        if (str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
