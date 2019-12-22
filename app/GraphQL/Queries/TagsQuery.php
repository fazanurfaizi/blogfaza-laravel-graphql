<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Tag;

class TagsQuery extends Query
{
    protected $attributes = [
        'name' => 'tags',
        'description' => 'A query for seeing all tags'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Tag'));
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
            return Tag::whereIn('id', $args['ids'])->with($with)->select($select)->get();
        }

        return Tag::all();
    }
}
