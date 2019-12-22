<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Tag;

use JWTAuth;
use Closure;
use GraphQL;
use App\Models\Tag;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

class DeleteTag extends Mutation
{
    protected $attributes = [
        'name' => 'deleteTag',
        'description' => 'A mutation for delete a tag'
    ];

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            $this->auth = null;
        }
        
        return (boolean) $this->auth;
    }

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
                    'required',
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

        $deleteTag = $tag->toArray();
        $tag->delete();
        
        return $deleteTag;
    }
}
