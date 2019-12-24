<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Category;

use Closure;
use GraphQL;
use GraphQL\Error\Error;
use App\Models\Category;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Traits\AuthorizationTrait;

class EditCategory extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'EditCategory',
        'description' => 'A mutation for edit category'
    ];

    public function type(): Type
    {
        return GraphQL::type('Category');
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
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',
                    'max:50'
                ]
            ]
        ];
    }

    public function validationErrorMessages(array $args = []): array {
        return [ 
            'id.required' => 'please enter the category id',
            'name.required' => 'please enter the category name',
            'name.max' => 'Category name must be less than 50 characters'
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $Category = Category::find($args['id']);
        if(!$Category){
            return new Error("Category Not Found");
        }

        $Category->name = $args['name'];
        $Category->save();
        return $Category;
    }
}
