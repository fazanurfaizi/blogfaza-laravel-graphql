<?php

declare(strict_types=1);

namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class ImageField extends Field
{
    protected $attributes = [
        'name' => 'ImageField',
        'description' => 'Image'
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array {
        return [
            'width' => [
                'type' => Type::int(),
                'description' => 'The Width of The Image'
            ],
            'height' => [
                'type' => Type::int(),
                'description' => 'The Height of The Image'
            ]
        ];
    }

    public function resolve($root, $args): string
    {
        if(!empty($args['width']) && !empty($args['height'])){
            return $root->imageUrl($args['width'], $args['height']);
        } else {
            return $root->imageUrl;
        }
    }
}
