<?php

namespace Trax\XapiServer\Routes;

use Trax\DataStore\Routes\DataRoutes;

class StatementRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Statement';
    
    /**
     * Only these API functions.
     */
    protected $only = array('get', 'store');



}
