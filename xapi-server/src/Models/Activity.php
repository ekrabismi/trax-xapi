<?php

namespace Trax\XapiServer\Models;

traxCreateModelSwitchClass('Trax\XapiServer\Models', 'trax-xapi-server', 'Activity');

class Activity extends ActivityModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'trax_xapiserver_activities';

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
