<?php
/**
 * SysEmpenho Active Record
 * @author  <your-name-here>
 */
class SysEmpenho extends TRecord
{
    const TABLENAME = 'sys_empenho';
    const PRIMARYKEY= 'idempenho';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $sys_equipamento;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numempenho');
        parent::addAttribute('objeto');
        parent::addAttribute('valor');
        parent::addAttribute('dataemp');
        parent::addAttribute('procorigem');
        parent::addAttribute('equipamento');
        parent::addAttribute('fonte');
    }

    
    /**
     * Method set_sys_equipamento
     * Sample of usage: $sys_empenho->sys_equipamento = $object;
     * @param $object Instance of SysEquipamento
     */
    public function set_sys_equipamento(SysEquipamento $object)
    {
        $this->sys_equipamento = $object;
        $this->sys_equipamento_id = $object->id;
    }
    
    /**
     * Method get_sys_equipamento
     * Sample of usage: $sys_empenho->sys_equipamento->attribute;
     * @returns SysEquipamento instance
     */
    public function get_sys_equipamento()
    {
        // loads the associated object
        if (empty($this->sys_equipamento))
            $this->sys_equipamento = new SysEquipamento($this->sys_equipamento_id);
    
        // returns the associated object
        return $this->sys_equipamento;
    }
    
  


}
