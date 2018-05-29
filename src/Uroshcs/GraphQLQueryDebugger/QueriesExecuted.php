<?php

namespace Uroshcs\GraphQLQueryDebugger;

class QueriesExecuted
{
    protected $queryList = [];

    /**
     * @param \Illuminate\Database\Events\QueryExecuted $query
     */
    public function push($query)
    {
        $this->queryList[] = [
            'sql' => $query->sql,
            'bindings' => '[' . implode(', ', $query->bindings) . ']',
            'time' => $query->time,
            'connection_name' => $query->connectionName
        ];
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->queryList;
    }
}