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

class UsersPaginateQuery extends Query
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'usersPerPage',
        'description' => 'A query for paginate the user'
    ];    

    public function type(): Type
    {
        return GraphQL::paginate('User');
    }

    public function args(): array
    {
        return [
            'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
                'rules' => [
                    'sometimes',
                    'min:1'
                ]
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int(),
                'rules' => [
                    'sometimes',
                    'min:1'
                ]
            ]
        ];
    }    

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $limit = !empty($args['limit']) ? $args['limit'] : 5;
        $page = !empty($args['page']) ? $args['page'] : 1;        
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return User::with($with)->select($select)->paginate($limit, ['*'], 'page', $page);
    }
}