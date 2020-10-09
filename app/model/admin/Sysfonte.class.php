<?php
/**
 * Sysfonte Active Record
 * @author  <your-name-here>
 */
class Sysfonte extends TRecord
{
    const TABLENAME = 'sysfonte';
    const PRIMARYKEY= 'idfonte';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('fonte');
        parent::addAttribute('fontedec');
    }


}
