<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class LuckyController
{
    public function number(): Response
    {
        $number = random_int(0, 100);
        $name = 'HELLO WORLD!';

        return new Response(
            '<html><body>Say: '.$name.'</body></html>'
        );
    }
}