<?php

namespace Trax\XapiServer\Models;

traxCreateModelSwitchClass('Trax\XapiServer\Models', 'trax-xapi-server', 'Statement');

class Statement extends StatementModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'trax_xapiserver_statements';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'data', 'voided',
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'voided', 'data', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'data' => 'object',
    ];

}
