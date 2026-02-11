<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Minify
{
    public function handle(Request $request, Closure $next): Response
    {
        if (setting('minify') == 1) {
            $segments = $request->segments();
            if (in_array('api', $segments)) { 
                return $next($request);
            }
            
            $response = $next($request);

            $contentType = $response->headers->get('Content-Type');
            if (str_contains($contentType, 'text/html')) {
                $response->setContent($this->minify($response->getContent()));
            }
    
            return $response;
        }
        
        return $next($request);
    }

    private function minify($input)
    {
        $search = array(
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        $output = preg_replace($search, $replace, $input);
        $output = preg_replace('!/\*.*?\*/!s', '', $output);
        $output = preg_replace('/\n\s*\n/', "\n", $output);

        return $output;
    }

}
