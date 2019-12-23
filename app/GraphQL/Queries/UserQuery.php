<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Closure;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Traits\AuthorizationTrait;

class UserQuery extends Query
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'user',
        'description' => 'A query of user'
    ];    

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => [
                    'required',
                    'numeric',
                    'min:1',
                    'exists:users,id'
                ]
            ]
        ];
    }    

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return User::where('id', '=', $args['id'])->with($with)->select($select)->firstOrFail();
    }
}
