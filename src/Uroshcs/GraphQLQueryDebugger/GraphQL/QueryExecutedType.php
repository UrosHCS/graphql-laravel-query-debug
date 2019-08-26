<?php

namespace Uroshcs\GraphQLQueryDebugger\GraphQL;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class QueryExecutedType extends GraphQLType
{
    protected $attributes = [
        'name' => 'query_executed',
        'description' => 'An executed query',
    ];

    public function fields() :array
    {
        return [
            'sql' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The SQL query that was executed'
            ],
            'bindings' => [
                'type' => Type::string(),
                'description' => 'The array of query bindings'
            ],
            'time' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The number of milliseconds it took to execute the query'
            ],
            'connection_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The database connection name'
            ],
        ];
    }
}
