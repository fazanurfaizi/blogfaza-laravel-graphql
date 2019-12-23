<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Tag;

use Closure;
use GraphQL;
use App\Models\Tag;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class CreateTag extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'createTag',
        'description' => 'A mutation for create tag query'
    ];    

    public function type(): Type
    {
        return GraphQL::type('Tag');
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',
                    'max:50'
                ]
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $tag = new Tag();
        $tag->fill($args);
        $tag->save();
        return $tag;
    }
}
