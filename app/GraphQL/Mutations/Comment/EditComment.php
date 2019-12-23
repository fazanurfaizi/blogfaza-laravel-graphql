<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Comment;

use Closure;
use GraphQL;
use GraphQL\Error\Error;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class EditComment extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'ceditComment',
        'description' => 'A mutation'
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
                    'required',
                    'numeric'
                ]
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::nonNull(Type::int()),
                'rules' => [
                    'required',                 
                    'exists:users,id'   
                ]
            ],
            'body' => [
                'name' => 'body',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required'
                ]
            ]
        ];
    }

    public function validationErrorMessages(array $args = []): array {
        return [ 
            'id.required' => 'please enter the category id',
            'user_id.exsist' => 'User Not Found'
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $comment = Comment::find($args['id']);
        $user = User::find($args['user_id']);

        if(!$comment){
            return new Error('Sorry, Comment no found.');
        }

        if(!$user){
            return new Error('User Not Found.');
        }

        if($user->id !== $comment->user_id){
            return new Error('User Not Matches.');
        }
        
        $comment->body = isset($args['body']) ? $args['body'] : $comment->body;
        $comment->save();
        return $comment;
    }
}
