<?php

namespace Uroshcs\GraphQLQueryDebugger\GraphQL;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class QueriesExecutedQuery extends Query
{
    protected $attributes = [
        'name' => 'Queries executed',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('query_executed'));
    }

    public function resolve()
    {
        return app('queries_executed')->all();
    }
}
