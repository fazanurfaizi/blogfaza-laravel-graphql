<?php

namespace App\GraphQL\Traits;

use Closure;
use Exception;
use JWTAuth;
use GraphQL\Type\Definition\ResolveInfo;
use App\Exceptions\AuthExpiredException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

trait AuthorizationTrait {

    protected $auth;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $exception) {
            throw new Exception($exception->getMessage());            
        } catch (Exception $exception) {
            $this->auth = null;
        } catch(AuthExpiredException $exception){
            throw new AuthExpiredException($exception->getMessage());
        }

        return (boolean) $this->auth;
    }
    

}