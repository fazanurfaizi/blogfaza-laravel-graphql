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

class DeleteCategory extends Mutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'DeleteCategory',
        'description' => 'A mutation'
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
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $category = Category::find($args['id']);
        if(!$category){
            return new Error("Category Not Found");
        }

        $deleteCategory = $category->toArray();
        $category->delete();

        return $deleteCategory;
    }
}
