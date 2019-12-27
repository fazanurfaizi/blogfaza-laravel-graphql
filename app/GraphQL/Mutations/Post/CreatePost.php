<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Post;

use Closure;
use GraphQL;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use App\Models\Category;
use GraphQL\Error\Error;
use Rebing\GraphQL\Support\UploadType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class CreatePost extends Mutation
{

    //use AuthorizationTrait;

    protected $attributes = [
        'name' => 'CreatePost',
        'description' => 'A mutation for create a post'
    ];

    public function type(): Type
    {
        return GraphQL::type('Post');
    }

    public function args(): array
    {
        return [
            'title' => [
                'name' => 'title',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',                    
                ]
            ], 
            'slug' => [
                'name' => 'slug',
                'type' => Type::string(),
                'rules' => [
                    'selectable' => false,
                    'sometimes'
                ],
                'resolve' => function($root, $args){
                    return str_slug($args['title']);
                }
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
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required'
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
                    'required',
                    'exists:categories,id'
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
        $user = User::findOrFail($args['user_id']);
        if($user->role_id == 1){
            $post = New Post();
            $post->fill($args);
            $post->slug = str_slug($post->title);       
            $post->user_id = $args['user_id'];
                             
            if(isset($args['image'])){
                $image = $args['image'];
                $uploadedImage = $image;
                $imageName = $post->slug . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/images/');
                $uploadedImage->move($destinationPath, $imageName);
                $image->imagePath = $destinationPath . $imageName;
                $post->image = $imageName;
            }    
            
            $post->save();
            $post->tags()->sync($args['tag_id']);

            return $post;
        } else {
            return new Error('Only, Admin who can create a new post');
        }
    }
}
