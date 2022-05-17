<?php 

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Signer\Hmac\Sha384;
use Lcobucci\JWT\Token\Builder;

class MercureCookieGenerator
{
    /**
     * @var string
     */
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }
    
    public function generate (User $user) 
    {

        $token = (new Builder())
            ->set('mercure', ['subscribe' => ["http://troc-service.fr/user/{$user->getProfile()->getId()}"]])
            ->sign(new Sha384(), $this->secret)
            ->getToken();
        return "mercureAuthorization={$token}; Path/hub; HttpOnly";
    }
}