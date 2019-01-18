<?php

namespace Trax\XapiServer\Stores;

use Trax\DataStore\Exceptions\NotFoundException;

traxCreateDataStoreSwitchClass('Trax\XapiServer\Stores', 'trax-xapi-server', 'Attachment');

class AttachmentStore extends AttachmentStoreSwitch
{
    
    /**
     * Store a data.
     */
    public function store($data, $options = array())
    {
        // Normalize input data
        $data = $this->normalizedJsonData($data);
        
        // Check if the attachment exists before storing it
        try {
            $attachment = $this->findBy('data.sha2', $data['sha2']);
            return false;
        } catch (\Exception $e) {
            return parent::store($data);
        }
    }
    
    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        // Get statements attachments
        if (isset($args['statements'])) {
            
            // Get attachment IDs from statements
            $ids = $this->attachementsIds($args['statements']);
            
            // Get matching attachments
            $search = array();
            $search['data.sha2'] = (object)array(
                'operator' => 'IN',
                'value' => $ids
            );
            $args['search'] = $search;
        }

        // Request the DB
        return parent::get($args, $options)->map(function ($record) {
            return $record->data;
        });
    }
    
    /**
     * Get attachment IDs from statements.
     */
    protected function attachementsIds($statements)
    {
        // Batch
        $ids = [];
        if (is_array($statements)) {
            foreach($statements as $statement) {
                $ids = array_merge($ids, $this->attachementsIds($statement));
            }
            return $ids;
        }
        
        // Single
        $statement = $statements;
        if (isset($statement->attachments)) {
            foreach($statement->attachments as $attachment) {
                $ids[] = $attachment->sha2;
            }
        }
        return $ids;
    }
    
}
