<?php
/**
 * SysEmpenho Active Record
 * @author  <your-name-here>
 */
class SysEmpenho extends TRecord
{
    const TABLENAME = 'sysempenho';
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
        parent::addAttribute('idequipamento');
        parent::addAttribute('fonte');
    }

    
    /**
     * Method set_sys_equipamento
     * Sample of usage: $sys_empenho->sys_equipamento = $object;
     * @param $object Instance of SysEquipamento
     */
    public function set_sysequipamento(SysEquipamento $object)
    {
        $this->sysequipamento = $object;
        $this->idequipamento = $object->id;
    }
    
    /**
     * Method get_sys_equipamento
     * Sample of usage: $sys_empenho->sys_equipamento->attribute;
     * @returns SysEquipamento instance
     */
    public function get_sysequipamento()
    {
        // loads the associated object
        if (empty($this->sysequipamento))
            $this->sysequipamento = new SysEquipamento($this->idequipamento);
    
        // returns the associated object
        return $this->sysequipamento;
    }
    
    
    /**
     * Method set_sys_fonte
     * Sample of usage: $sys_empenho->sys_equipamento = $object;
     * @param $object Instance of SysEquipamento
     */
    public function set_sysfonte(SysFonte $object)
    {
        $this->sysfonte = $object;
        $this->idfonte = $object->id;
    }
    
    /**
     * Method get_sys_equipamento
     * Sample of usage: $sys_empenho->sys_equipamento->attribute;
     * @returns SysEquipamento instance
     */
    public function get_sysfonte()
    {
        // loads the associated object
        if (empty($this->sysfonte))
            $this->sysfonte = new SysFonte($this->idfonte);
    
        // returns the associated object
        return $this->sysfonte;
    }
    
  


}
