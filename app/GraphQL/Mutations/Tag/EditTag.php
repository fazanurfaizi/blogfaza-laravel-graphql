<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Tag;

use Closure;
use GraphQL;
use App\Models\Tag;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class EditTag extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'EditTag',
        'description' => 'A mutation for edit a tag'
    ];

    public function type(): Type
    {
        return GraphQL::type('Tag');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                'rules' => [
                    'required'
                ]
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'rules' => [
                    'required',
                    'max:50'
                ]
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $tag = Tag::find($args['id']);
        if(!$tag){
            return new Error("Tag Not Found");
        }

        $tag->fill($args);
        $tag->save();
        return $tag;
    }
}
