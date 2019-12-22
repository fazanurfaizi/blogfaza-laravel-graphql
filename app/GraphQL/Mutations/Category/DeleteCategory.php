<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Category;

use JWTAuth;
use Closure;
use GraphQL;
use GraphQL\Error\Error;
use App\Models\Category;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;
class DeleteCategory extends Mutation
{
    protected $attributes = [
        'name' => 'deleteCategory',
        'description' => 'A mutation'
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
