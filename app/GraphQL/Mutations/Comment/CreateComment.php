<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Comment;

use JWTAuth;
use Closure;
use GraphQL;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\InputObjectType;
use App\GraphQL\Traits\AuthorizationTrait;

class CreateComment extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'CreateComment',
        'description' => 'A mutation for create comment'
    ];    

    public function type(): Type
    {
        return GraphQL::type('Comment');
    }

    public function args(): array
    {
        return [
            'post_id' => [
                'name' => 'post_id',
                'type' => Type::nonNull(Type::int()),
                'rules' => [
                    'required',
                    'exists:posts,id',
                    'selectable' => false
                ]
            ],          
            'body' => [
                'name' => 'body',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',
                    'max:1000'
                ]
            ],            
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {      
        $comment = new Comment();
        $comment->fill($args);
        $user = JWTAuth::parseToken()->authenticate();
        $comment->user_id = $user->id;
        
        $comment->save();
        return $comment;
    }
}
