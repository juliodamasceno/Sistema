<?php
/**
 * SystemFornecedor Active Record
 * @author  <your-name-here>
 */
class SystemFornecedor extends TRecord
{
    const TABLENAME = 'sys_fornecedor';
    const PRIMARYKEY= 'idfornecedor';
    const IDPOLICY =  'max'; // {max, serial}
    
    
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


}
