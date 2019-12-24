<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Closure;
use GraphQL;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class UsersQuery extends Query
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'users',
        'description' => 'A query of users' 
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('User'));
    }


    public function args(): array
    {
        return [
            'ids' => [
                'name' => 'ids',
                'type' => Type::listOf(Type::int()),
                'rules' => [
                    'sometimes',
                    'array'                    
                ]
            ]
        ];
    }    

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if(isset($args['ids'])){
            return User::whereIn('id', $args['ids'])->with($with)->select($select)->get();
        }

        return User::all();
    }
}
