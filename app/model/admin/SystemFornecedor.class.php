<?php
/**
 * SystemFornecedor Active Record
 * @author  <your-name-here>
 */
class SystemFornecedor extends TRecord
{
    const TABLENAME = 'sysfornecedor';
    const PRIMARYKEY= 'idfornecedor';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $equipamento;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cnpj');
        parent::addAttribute('fornecedor');
        parent::addAttribute('datacadastro');
         parent::addAttribute('email');
        parent::addAttribute('telefone');
         parent::addAttribute('ativo');
        parent::addAttribute('tipo');
    }
    
    /**
     * Method set_tomador
     * Sample of usage: $apolice->tomador = $object;
     * @param $object Instance of Tomador
     */
    public function set_equipamento(Equipamento $object)
    {
        $this->equipamento = $object;
        $this->equipamneto_id = $object->id;
    }
    
    /**
     * Method get_tomador
     * Sample of usage: $apolice->tomador->attribute;
     * @returns Tomador instance
     */
    public function get_equipamento()
    {
        // loads the associated object
        if (empty($this->equipamento))
            $this->equipamento = new Equipamento($this->equipamento_id);
    
        // returns the associated object
        return $this->equipamento;
    }
    
    
    /**
     * Method set_funcionario
     * Sample of usage: $apolice->funcionario = $object;
     * @param $object Instance of Funcionario
     */


}
