<?php

declare(strict_types=1);

namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class AvatarField extends Field
{
    protected $attributes = [
        'name' => 'AvatarField',
        'description' => 'User Avatar Image',
        'selectable' => false
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array {
        return [
            'size' => [
                'type' => Type::int(),
                'description' => 'The size of the user avatar'
            ]
        ];
    }

    public function resolve($root, $args): string
    {
        return isset($args['size']) ? $root->avatar($args['size']) : $root->avatar;
    }
}
