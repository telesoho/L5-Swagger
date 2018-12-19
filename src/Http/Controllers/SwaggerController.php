<?php

namespace L5Swagger\Http\Controllers;

use File;
use Request;
use Response;
use L5Swagger\Generator;
use Illuminate\Routing\Controller as BaseController;

class SwaggerController extends BaseController
{
    /**
     * Dump api-docs.json content endpoint.
     *
     * @param string $jsonFile
     *
     * @return \Response
     */
    public function docs($groupName, $jsonFile = null)
    {
        $pathDocs = config("l5-swagger.paths.docs").'/'.$groupName;
        $jsonFile = !is_null($jsonFile) ? $jsonFile: config("l5-swagger.paths.docs_json", 'api-docs.json');
        $filePath = $pathDocs.'/'.$jsonFile;

        if (! File::exists($filePath)) {
            abort(404, 'Cannot find '.$filePath);
        }

        $content = File::get($filePath);

        return Response::make($content, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Display Swagger API page.
     *
     * @return \Response
     */
    public function api($groupName)
    {
        if (config('l5-swagger.generate_always')) {
            Generator::generateDocs($groupName);
        }

        if ($proxy = config('l5-swagger.proxy')) {
            if (! is_array($proxy)) {
                $proxy = [$proxy];
            }
            Request::setTrustedProxies($proxy, \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL);
        }

        // Need the / at the end to avoid CORS errors on Homestead systems.
        $urlToDocs = route("l5-swagger.docs", $groupName, config("l5-swagger.paths.docs_json", 'api-docs.json'));
        $response = Response::make(
            view('l5-swagger::index', [
                'secure' => Request::secure(),
                'urlToDocs' => $urlToDocs,
                'operationsSorter' => config('l5-swagger.operations_sort'),
                'configUrl' => config('l5-swagger.additional_config_url'),
                'validatorUrl' => config('l5-swagger.validator_url'),
                'groupName' => $groupName,
            ]),
            200
        );

        return $response;
    }

    /**
     * Display Oauth2 callback pages.
     *
     * @return string
     */
    public function oauth2Callback($groupName)
    {
        return \File::get(swagger_ui_dist_path('oauth2-redirect.html'));
    }
}
