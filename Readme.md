# Laravel GraphQL

A simple package that creates a graphql query type that enables fetching the list of executed database queries. This can be used to check which database queries were executed and how many of them.

This package requires an installed and published (`php artisan vendor:publish ...`) [Rebing's graphql-laravel](https://github.com/rebing/graphql-laravel) package. If you don't have this package installed and published, do that first.
That way you will have `app/config/graphql.php` file, which needs to be edited.

## Installation

#### Dependencies:

* [Rebing's graphql-laravel](https://github.com/rebing/graphql-laravel)

#### Installation:

**1-** Require the package via Composer in your `composer.json`.
```json
{
  "require": {
    "uroshcs/graphql-laravel-query-debug": "dev-master"
  }
}
```

**2-** Run Composer to install or update the new requirement.

```bash
$ composer install
```

or

```bash
$ composer update
```

**3-** Add the service provider to your `app/config/app.php` file
```php
Uroshcs\GraphQLQueryDebugger\DebuggerServiceProvider::class,
```

**4-** Add `queries_executed` query to your `app/config/graphql.php` file
```php
'schemas' => [
    'default' => [
        'query' => [
            // other query types
            'queries_executed' => Uroshcs\GraphQLQueryDebugger\GraphQL\QueriesExecutedQuery::class,
        ],
        // mutations, middleware
    ],
],
```

**5-** Add `query_executed` type to your `app/config/graphql.php` file
```php
'types' => [
    // other types
    'query_executed' => Uroshcs\GraphQLQueryDebugger\GraphQL\QueryExecutedType::class,
],
```

## Usage

The full query with all possible fields looks like this:
```graphql
{
    # other fields

    queries_executed {
        sql
        bindings
        time
        connection_name
    }
}
```

In order for this package to do it's job correctly, the `queries_executed` needs to be the last query in order to accumulate the database queries from other types.

For example, a query like this:

```graphql
{
    users (limit: 10, page: 1) {
        total
        data {
            id
            username
            email
            posts_count
        }
    }

    queries_executed {
        sql
        bindings
        time
        connection_name
    }
}
```

should give a response like this:

```json
{
  "data": {
    "users": {
      "total": 58,
      "data": [
        {
          "id": 1,
          "username": "john.smith",
          "email": "john.smith@example.com",
          "posts_count": 12
        },
        // ...
      ]
    },
    "queries_executed": [
      {
        "sql": "select count(*) as aggregate from `users`",
        "bindings": "[]",
        "time": 91.07,
        "connection_name": "mysql"
      },
      {
        "sql": "select `users`.`id`, `users`.`username`, `users`.`email`, (select count(*) from `posts` where `users`.`id` = `posts`.`user_id`) as `posts_count` from `users` limit 10 offset 0",
        "bindings": "[]",
        "time": 39.58,
        "connection_name": "mysql"
      }
    ]
  }
}
```