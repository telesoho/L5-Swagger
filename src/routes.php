<?php
$router->get(config('l5-swagger.routes.docs') . '/asset/{asset}', [
    'as' => 'l5-swagger.asset',
    'middleware' => config('l5-swagger.routes.middleware.asset', []),
    'uses' => '\L5Swagger\Http\Controllers\SwaggerAssetController@index',
]);

if (config('l5-swagger.doc_group')) {
    $router->any(
        config('l5-swagger.routes.docs').'/{groupName}'.'/{jsonFile?}',
        [
            'as' => 'l5-swagger.docs',
            'middleware' => config('l5-swagger.routes.middleware.docs', []),
            'uses' => '\L5Swagger\Http\Controllers\SwaggerController@docs',
        ]
    );
    $router->get(config('l5-swagger.routes.api').'/{groupName}', [
        'as' => 'l5-swagger.api',
        'middleware' => config('l5-swagger.routes.middleware.api', []),
        'uses' => '\L5Swagger\Http\Controllers\SwaggerController@api',
    ]);
    $router->get(config('l5-swagger.routes.oauth2_callback').'/{groupName}', [
        'as' => 'l5-swagger.oauth2_callback',
        'middleware' => config('l5-swagger.routes.middleware.oauth2_callback', []),
        'uses' => '\L5Swagger\Http\Controllers\SwaggerController@oauth2Callback',
    ]);
} else {
    $router->get(config('l5-swagger.routes.api'), [
        'as' => 'l5-swagger.api',
        'middleware' => config('l5-swagger.routes.middleware.api', []),
        'uses' => '\L5Swagger\Http\Controllers\SwaggerController@api',
    ]);

    $router->any(config('l5-swagger.routes.docs').'/{jsonFile?}', [
        'as' => 'l5-swagger.docs',
        'middleware' => config('l5-swagger.routes.middleware.docs', []),
        'uses' => '\L5Swagger\Http\Controllers\SwaggerController@docs',
    ]);

    $router->get(config('l5-swagger.routes.oauth2_callback'), [
        'as' => 'l5-swagger.oauth2_callback',
        'middleware' => config('l5-swagger.routes.middleware.oauth2_callback', []),
        'uses' => '\L5Swagger\Http\Controllers\SwaggerController@oauth2Callback',
    ]);
}
