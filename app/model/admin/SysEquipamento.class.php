<?php
/**
 * SysEquipamento Active Record
 * @author  <your-name-here>
 */
class SysEquipamento extends TRecord
{
    const TABLENAME = 'sysequipamento';
    const PRIMARYKEY= 'idequipamento';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nomeequipamento');
        parent::addAttribute('sigla');
    }


}
