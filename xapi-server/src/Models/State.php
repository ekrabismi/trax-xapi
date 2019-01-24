<?php

namespace Trax\XapiServer\Models;

traxCreateModelSwitchClass('Trax\XapiServer\Models', 'trax-xapi-server', 'State');

class State extends StateModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'trax_xapiserver_states';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'data',
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'data', 'created_at', 'updated_at'
    ];

}
