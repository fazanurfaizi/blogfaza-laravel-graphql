<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use App\Models\Post;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\GraphQL\Fields\ImageField;

class PostType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Post',
        'description' => 'A type for post query',
        'model' => Post::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'decription' => 'the id of post'
            ],
            'category_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the id of category'
            ],
            'category' => [
                'type' => GraphQL::type('Category'),
                'description' => 'the category of post'
            ],
            'user_id' => [
                'type' => Type::nonNull(Type::int()),
                'decription' => 'the id of user'
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'the post users'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the slug of post',
                'selectable' => false,
                'resolve' => function($root, $args){
                    return str_slug($root->title);
                }
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the title of post'
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'description of the post'
            ],
            'body' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'body of the post'
            ],
            'related_posts' => [
                'type' => Type::listOf(GraphQL::type('Post')),
                'description' => 'related posts',
                'args' => [
                    'limit' => [
                        'type' => Type::int(),
                        'description' => 'number of related posts'
                    ]
                ],
                'selectable' => false,
                'resolve' => function($root, $args){
                    return (isset($args['limit'])) ? $root->relatedPosts->take($args['limit']) : $root->relatedPosts;
                }
            ],
            'comments' => [
                'type' => Type::listOf(GraphQL::type('Comment')),
                'description' => 'comment of the post'
            ],
            'comment_counts' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the number of comments on the post',
                'selectable' => false,
                'resolve' => function($root, $args){
                    return $root->comments->count();
                }
            ],
            'views' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the number of views on the post',
            ],
            'tags' => [
                'type' => Type::listOf(GraphQL::type('Tag')),
                'description' => 'tag of the post'
            ],
            'created_at' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'timestamp for creating the post',
                'resolve' => function($root, $args){
                    return (string) $root->created_at;
                }
            ],
            'updated_at' => [
                'type' => Type::nonNull(type::string()),
                'description' => 'timestamp for updating the post',
                'resolve' => function($root, $args){
                    return (string) $root->updated_at;
                }
            ],
            'image' => ImageField::class
        ];
    }
}
