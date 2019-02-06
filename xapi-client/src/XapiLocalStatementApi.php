<?php

namespace Trax\XapiClient;

use XapiServer;

class XapiLocalStatementApi
{    

    /**
     * Record one or more Statements.
     */
    public function record($statements, $attachments = [])
    {
        $statements = json_decode(json_encode($statements));
        return XapiServer::xapiStores()->recordStatements($statements, $attachments);
    }
    

}
