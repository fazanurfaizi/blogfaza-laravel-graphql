<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Closure;
use GraphQL;
use App\Models\Role;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class RolesQuery extends Query
{
    protected $attributes = [
        'name' => 'roles',
        'description' => 'A query of roles'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Role'));
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
            return Role::whereIn('id', $args['ids'])->with($with)->select($select)->get();
        }

        return Role::all();
    }
}
