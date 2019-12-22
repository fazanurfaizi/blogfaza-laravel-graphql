<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Post;

class PostsPaginateQuery extends Query
{
    protected $attributes = [
        'name' => 'postsPerPage',
        'description' => 'A query for paginate the posts'
    ];

    public function type(): Type
    {
        return GraphQL::paginate('Post');
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

        return Post::with($with)->select($select)->paginate($limit, ['*'], 'page', $page);
    }
}