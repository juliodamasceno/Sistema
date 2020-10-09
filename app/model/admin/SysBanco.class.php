<?php
/**
 * SysBanco Active Record
 * @author  <your-name-here>
 */
class SysBanco extends TRecord
{
    const TABLENAME = 'sysbanco';
    const PRIMARYKEY= 'idbanco';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nomebanco');
    }


}
