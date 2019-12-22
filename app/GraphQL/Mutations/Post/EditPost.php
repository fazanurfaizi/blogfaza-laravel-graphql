<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Post;

use JWTAuth;
use Closure;
use GraphQL;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use App\Models\Category;
use Carbon\Carbon;
use GraphQL\Error\Error;
use Rebing\GraphQL\Support\UploadType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class EditPost extends Mutation
{
    protected $attributes = [
        'name' => 'editPost',
        'description' => 'A mutation for edit a post'
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
                    'required',
                    'numeric'
                ]
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::nonNull(Type::int()),
                'rules' => [
                    'required'
                ]
            ],
            'title' => [
                'name' => 'title',
                'type' => Type::string(),
                'rules' => [
                    'sometimes'
                ]
            ],
            'description' => [
                'name' => 'description',
                'type' => Type::string(),
                'rules' => [
                    'sometimes'
                ]
            ],
            'body' => [
                'name' => 'body',
                'type' => Type::string(),
                'rules' => [
                    'sometimes'
                ]
            ],
            'image' => [
                'name' => 'image',
                'type' => GraphQL::type('Upload'),
                'rules' => [
                    'sometimes',
                    'image'
                ]
            ],
            'category_id' => [
                'name' => 'category_id',
                'type' => Type::int(),
                'rules' => [
                    'sometimes',
                    'exists:categories,id'
                ]                
            ],
            'tag_id' => [
                'name' => 'tag_id',
                'type' => Type::listOf(Type::int()),
                'rules' => [
                    'sometimes',
                    'array'
                ]
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $post = Post::find($args['id']);
        $user = User::find($args['user_id']);

        if(!$post){
            return new Error("Sorry, Post not found.");
        }

        if(!$user){
            return new Error("Sorry, User not found.");
        }

        if($user->id !== $post->user_id){
            return new Error("Sorry, This post just only can edit by author.");
        }

        $title = isset($args['title']) ? $args['title'] : $post->title;
        $slug = str_slug($title);

        $post->title = $title;
        $post->slug = $slug;
        $post->description = isset($args['description']) ? $args['description'] : $post->description;
        $post->body = isset($args['body']) ? $args['body'] : $post->body;        

        if(!isset($args['image'])){
            $post->image = $post->image;
        } else {
            $image = $args['image'];
            $uploadedImage = $image;
            $imageName = $post->slug . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/images/');
            $uploadedImage->move($destinationPath, $imageName);
            $image->imagePath = $destinationPath . $imageName;
            unlink(public_path('/storage/images/') . $post->image);
            $post->image = $imageName;
        }

        $post->category_id = isset($args['category_id']) ? $args['category_id'] : $post->category_id;
        $post->updated_at = Carbon::now();
        $post->save();

        if(isset($args['tag_id'])){
            $post->tags()->sync($args['tag_id']);
        }        

        return $post;
    }
}
