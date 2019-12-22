<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Post;

use JWTAuth;
use Closure;
use GraphQL;
use App\Models\Post;
use App\Models\User;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeletePost extends Mutation
{
    protected $attributes = [
        'name' => 'deletePost',
        'description' => 'A mutation for delete post'
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
        return GraphQL::type('Post');
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
        $post = Post::find($args['id']);

        if(!$post){
            return new Error("Sorry, Post not found.");
        }

        if(!$user){
            return new Error('User Not Found');
        }        

        if($user->id !== $post->user_id){
            return new Error('User Not Matches');
        }                    

        $deletePost = $post->toArray();
        unlink(public_path('/storage/images/') . $post->image);
        $post->delete();

        return $deletePost;
    }
}
