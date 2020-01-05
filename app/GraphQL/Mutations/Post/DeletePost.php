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
use App\GraphQL\Traits\AuthorizationTrait;

class DeletePost extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'DeletePost',
        'description' => 'A mutation for delete post'
    ];    

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
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($args['id']);

        if(!$post){
            return new Error("Sorry, Post not found.");
        }

        if(!$user){
            return new Error('User Not Found');
        }        

        if($user->role_id !== 1){
            return new Error('Only Admin who can delete this post');
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
