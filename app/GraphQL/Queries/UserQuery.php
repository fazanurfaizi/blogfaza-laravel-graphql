<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use JWTAuth;
use Closure;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Traits\AuthorizationTrait;

class UserQuery extends Query
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'user',
        'description' => 'A query of user'
    ];    

    public function type(): Type
    {
        return GraphQL::type('User');
    }  

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();
        $user = JWTAuth::parseToken()->authenticate();       

        return User::where('id', '=', $user->id)->with($with)->select($select)->firstOrFail();
    }
}
