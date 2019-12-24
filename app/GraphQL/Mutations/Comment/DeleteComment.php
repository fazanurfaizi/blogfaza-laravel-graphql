<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Comment;

use JWTAuth;
use GraphQL;
use Closure;
use App\Models\User;
use App\Models\Comment;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class DeleteComment extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'DeleteComment',
        'description' => 'A mutation for delete a comment'
    ];

    public function type(): Type
    {
        return GraphQL::type('Comment');
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
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::nonNull(Type::int()),
                'rules' => [
                    'required'
                ]
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {   
        $user = User::find($args['user_id']);

        if(!$user){
            return new Error('User Not Found');
        }

        if($user->id !== $comment->user_id){
            return new Error('User Not Matches');
        }    

        $comment = Comment::find($args['id']);
        if(!$comment){
            return null;
        }

        $deleteComment = $comment->toArray();
        $comment->delete();

        return $deleteComment;
    }
}
