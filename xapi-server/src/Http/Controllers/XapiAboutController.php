<?php

namespace Trax\XapiServer\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class XapiAboutController extends Controller
{

    /**
     * Get about data.
     */
    public function about(Request $request)
    {
        $about = array(
            'version' => ['1.0.3'],
            'extensions' => [
                'http://xapi.fr/vocab/extensions/about/product' => 'Trax LRS',
                'http://xapi.fr/vocab/extensions/about/version' => '1.0.0',
                'http://xapi.fr/vocab/extensions/about/date' => '2019-01-01',
                'http://xapi.fr/vocab/extensions/about/author' => 'Sébastien FRAYSSE',
                'http://xapi.fr/vocab/extensions/about/licence' => 'EUPL 1.2',
            ]
        );
        return response()->json($about);
    }

        
}
