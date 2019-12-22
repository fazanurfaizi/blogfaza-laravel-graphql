<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Comment',
        'description' => 'A type for comment query',
        'model' => Comment::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the id of comment'
            ],            
            'body' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the body of comment'
            ],
            'user_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the user id'
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'The user who comment on this post'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The User name while comment as guest'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The User email while comment as guest'
            ],
            'post_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the post id'
            ],
            'post' => [
                'type' => GraphQL::type('Post'),
                'decription' => 'The post that have comments'
            ],
            'created_at' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the timestamp for creating the post comment',
                'args' => [
                    'diffForHumans' => [
                        'type' => Type::boolean(),
                        'description' => 'Time different for humans',
                    ]
                ],
                'resolve' => function($root, $args){
                    return isset($args["diffForHumans"]) && $args["diffForHumans"] == true ? $root->created_at->diffForHumans() : (string) $root->created_at;
                }
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'the timestamp for updating the post',
                'resolve' => function($root, $args){
                    return (string) $root->updated_at;
                }
            ]
        ];
    }
}
