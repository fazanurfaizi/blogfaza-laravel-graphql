<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Comment;

class CommentsQuery extends Query
{
    protected $attributes = [
        'name' => 'comments',
        'description' => 'A query for seeing all comments'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Comment'));
    }

    public function args(): array
    {
        return [
            'post_id' => [
                'name' => 'post_id',
                'type' => Type::int(),
                'rules' => [
                    'sometimes',
                    'exists:posts,id'
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

        if(isset($args['post_id'])){
            return Comment::where('post_id', $args['post_id'])->get();
        }

        return Comment::all();
    }
}
