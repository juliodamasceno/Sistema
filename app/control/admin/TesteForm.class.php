<?php
class TesteForm extends TPage
{
   
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_cadastro_pessoas');
        $this->form->setFormTitle(('Cadastro de Pessoas Físicas e Juridicas'));
        $this->form->setFieldSizes('100%');
        $this->form->setClientValidation(true);
        
        
  // create the form fields
         
          $id               = new TEntry('id');
          $datacadastro         = new TEntry('datacadastro');
          $datacadastro->setValue(date("d/m/Y"));       
          $novo = TSession::getValue('login');
          $statuscadastro       = new TEntry('statuscadastro');
          $motivo                = new TDBUniqueSearch('motivo', 'db_isis360', 'tb_CadastroPessoasMotivo', 'id', 'motivo');
          $relacionamento       = new TDBUniqueSearch('relacionamento', 'db_isis360', 'tb_CadastroPessoasRelacionamento', 'id', 'relacionamento');
           $pfoupj                = new TCombo('pfoupj');
          // $pfoupj                = new TRadioGroup('pfoupj');
          $cpf                    = new TEntry('cpf');
          //$cpf->addValidation('cpf', new TCPFValidator);
          $rg                    = new TEntry('rg');     
          $cnpj                   = new TEntry('cnpj');    
          //$cnpj->addValidation('cpf', new TCNPJValidator);
          $cpfcnpj               = new TEntry('cpfcnpj');    
          $inscricaomunicipal   = new TEntry('inscricaomunicipal');    
          $inscricaoestadual    = new TEntry('inscricaoestadual'); 
          $nome                   = new TEntry('nome');  
          //$nome->addValidation('Nome', new TMinLengthValidator, array(3));          
          $nomesocial            = new TEntry('nomesocial');    
          $razaosocial            = new TEntry('razaosocial');
          $dataabertura            = new TEntry('dataabertura');
          $situacaocnpj         = new TEntry('situacaocnpj'); 
          $naturezajuridica        = new TEntry('naturezajuridica');
          $porte                = new TEntry('porte'); 
          //$razaosocial->addValidation('Razão Social', new TMinLengthValidator, array(3));
          $fantasia                = new TEntry('fantasia');    
          $genero                = new TCombo('genero');
          $nascimento            = new TDate('nascimento');
          $estadocivil            = new TCombo('estadocivil');
          $nacionalidade        = new TEntry('nacionalidade');
          $cep                    = new TEntry('cep');
          $logradouro            = new TEntry('logradouro');
          $numero                 = new TEntry('numero');
          $bairro                = new TEntry('bairro');
          $complemento            = new TEntry('complemento');
          $cidade               = new TEntry('cidade');
          $estado                = new TEntry('estado');
          $telefonecnpj         = new TEntry('telefonecnpj');
          $telefonefixo            = new TEntry('telefonefixo');
          $telefonecelular      = new TEntry('telefonecelular');
          $whatsapp             = new TEntry('whatsapp');
          $email                = new TEntry('email');       
          $emailcnpj            = new TEntry('emailcnpj');
          $email->addValidation('Email', new TEmailValidator);
          $site                 = new TEntry('site');
          $facebook                = new TEntry('facebook');
          $linkedin             = new TEntry('linkedin');
          $ieisento                = new TCombo('ieisento');
          $suframa                = new TEntry('suframa');
          $observacao              = new TText('observacao');
          $grupo    = new TDBUniqueSearch('grupo', 'db_isis360', 'tb_CadastroPessoasGrupo', 'id', 'grupo');
          
       
          $pfoupj->setChangeAction(new TAction(array($this, 'onChangePFouPJ')));
          $combo_items = array();
          $combo_items['f'] ='Física';
          $combo_items['j'] ='Jurídica';
          $pfoupj->addItems($combo_items);
          $pfoupj->setValue('f');
          //$pfoupj->setLayout('horizontal');
          
          self::onChangePFouPJ( ['pfoupj' => 'f'] );
          
        
           $ieisento->setChangeAction(new TAction(array($this, 'onChangeIEIsento')));
          $combo_items = array();
          $combo_items['i'] ='Isento';
          $combo_items['t'] ='Tributável';
          $ieisento->addItems($combo_items);
          $ieisento->setValue('t');
          
            
    
           
        // TEntry::disableField('form_cadastro_pessoas', 'cnpj'); 
          
          
          
          $motivo->setChangeAction(new TAction(array($this, 'onChangeMotivo')));
          $statuscadastro->setEditable(FALSE);        
        // default value
       ;
        
          // fire change event
         // self::onChangePFouPJ( ['pfoupj' => 'Juridica'] );
        
        // add the combo options
        $estadocivil->addItems( [ 'S' => 'Solteiro(a)', 'U' => 'União Estável', 'C' => 'Casado(a)', 'E' => 'Separado(a)', 'D' => 'Divorciado(a)', 'V' => 'Viúvo(a)' ] );
//        $relacionamento->addItems( [ 'C' => 'Cliente', 'F' => 'Fornecedor', 'L' => 'Colaboador', 'T' => 'Transportador', 'Z' => 'Terceirizado' ] );
        $genero->addItems( [ 'M' => 'Masculino', 'F' => 'Feminino' ] );
        //$genero->setLayout('horizontal');
        
        // define some properties for the form fields
        $id->setEditable(FALSE);
        $id->setSize('100%');
        
        $cidade->setSize('100%');
        $observacao->setSize('300%', 100);
   
        $nascimento->setSize('100%');
        
        $logradouro->setSize('100%');
        $numero->setSize('30%');
        
        //$cidade->enableSearch();
        //$cidade->setMinLength(0);
        $relacionamento->setMinLength(0);
        $grupo->setMinLength(0);
        $motivo->setMinLength(0);
        
        //$estado->setMinLength(0);
        //$estado->setMask('{nome_estado} - <b>{uf}</b>');
        
        $nascimento->setMask('dd/mm/yyyy');
        $cnpj->setMask('99.999.999/9999-99');
        $cpf->setMask('999.999.999-99');
        $cep->setMask('99.999-999');
        $telefonefixo->setMask('(99) 9999.9999');
        $telefonecnpj->setMask('(99) 9999.9999');
        $telefonecelular->setMask('(99) 99999.9999');
        $whatsapp->setMask('(99) 99999.9999');
    
        // insert in form fields
        
        $this->form->appendPage('CADASTRO');
        
        
        $row = $this->form->addFields( [ new TLabel('Código'),     $id ],
                                       [ new TLabel('Tipo de Pessoa'),     $pfoupj ],
                                       [ new TLabel('Relacionamento'),     $relacionamento ],
                                       [ new TLabel('Status'),   $statuscadastro ]);
                                       
        $row->layout = ['col-sm-2','col-sm-4','col-sm-3','col-sm-3'];
    
         
        $row = $this->form->addFields( [ new TLabel('CPF'),     $cpf ],
                                       [ new TLabel('RG'),   $rg ]);
                                       
        $row->layout = ['col-sm-6','col-sm-6'];
        
        $row = $this->form->addFields( [ new TLabel('CNPJ'),     $cnpj ],
                                       [ new TLabel('Razao Social'),  $razaosocial ],
                                       [ new TLabel('Fantasia'),     $fantasia ]);
        $row->layout = ['col-sm-2','col-sm-6','col-sm-4'];
        
        $row = $this->form->addFields( [ new TLabel('Nome'),            $nome ],
                                       [ new TLabel('Nome Social'),     $nomesocial ]);
        $row->layout = ['col-sm-6', 'col-sm-6'];
        
        $row = $this->form->addFields( [ new TLabel('Data Abertura'),     $dataabertura ],
                                       [ new TLabel('Natureza Juridica'),     $naturezajuridica ],
                                       [ new TLabel('Porte'),     $porte ],
                                       [ new TLabel('Situação CNPJ'),     $situacaocnpj ]);
        $row->layout = ['col-sm-2','col-sm-4','col-sm-4','col-sm-2'];
        
        $row = $this->form->addFields( [ new TLabel('Gênero'),           $genero ],
                                       [ new TLabel('Estado Civil'),     $estadocivil ],
                                       [ new TLabel('Nascimento'),       $nascimento ] );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];
        
        $row = $this->form->addFields( [ new TLabel('Grupo / Categoria'),           $grupo ],
                                       [ new TLabel('Motivo'),                      $motivo ],
                                       [ new TLabel('Data do Cadastro'),      $datacadastro ]);
        $row->layout = ['col-sm-4','col-sm-4','col-sm-4'];
         
        $row = $this->form->addFields( [ new TLabel('Infomações Adicionai'),           $observacao ]);
        $row->layout = ['col-sm-12'];
   
        $this->form->appendPage('ENDEREÇO');
        
        $row = $this->form->addFields( [ new TLabel('CEP'),          $cep ],
                                       [ new TLabel('Logradouro'),   $logradouro ],
                                       [ new TLabel('Número'),       $numero ]);
        $row->layout = ['col-sm-2', 'col-sm-8', 'col-sm-2'];
        
        $row = $this->form->addFields( [ new TLabel('Complemento'),$complemento ],
                                       [ new TLabel('Bairro'),     $bairro ],
                                       [ new TLabel('Cidade'),     $cidade ],
                                       [ new TLabel('Estado'),     $estado ] );
        $row->layout = ['col-sm-4', 'col-sm-3', 'col-sm-3','col-sm-2'];
                
        $this->form->appendPage('FISCAL');
        
        $row = $this->form->addFields( [ new TLabel('Inscrição Municipal'),   $inscricaomunicipal ],
                                       [ new TLabel('I.E. Isento'),   $ieisento ],
                                       [ new TLabel('Inscrição Estadual'),   $inscricaoestadual ]);
        $row->layout = ['col-sm-4', 'col-sm-4','col-sm-4'];
        
        $row = $this->form->addFields( [ new TLabel('Suframa'),          $suframa ]);
  
        $row->layout = ['col-sm-6'];
    
    
        $this->form->appendPage('CONTATOS');        
        $row = $this->form->addFields( [ new TLabel('Fone Cadastro CNPJ'),          $telefonecnpj ],
                                       [ new TLabel('Email Cadastro CNPJ'),          $emailcnpj ]);
        $row->layout = ['col-sm-4', 'col-sm-8'];
        
        $row = $this->form->addFields( [ new TLabel('Telefone'),          $telefonefixo ],
                                       [ new TLabel('Celular'),           $telefonecelular ],
                                       [ new TLabel('WhatsApp'),          $whatsapp ]);  
                                       
        $row->layout = ['col-sm-4', 'col-sm-4','col-sm-4',];
        
        
        $row = $this->form->addFields( [ new TLabel('E-Mail'),            $email ] );
        $row->layout = ['col-sm-6'];
        $row = $this->form->addFields( [ new TLabel('site'),          $site ],
                                       [ new TLabel('facebook'),      $facebook ],
                                       [ new TLabel('Linkedin'),      $linkedin ] );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];
        
        
    
        $action_cep = new TAction(array($this,'onCep'));
        $cep->setExitAction($action_cep);    
        
        $action_cnpj = new TAction(array($this,'onCNPJ'));
        $cnpj->setExitAction($action_cnpj);
        
        
        $btn = $this->form->addAction( 'Salvar', new TAction(array($this, 'onSave')), 'fa:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink(_t('Clear'), new TAction(array($this, 'onClear')), 'fa:eraser red');
        
        $this->form->addActionLink( _t('Back'), new TAction(array('CadastroPessoasListNew','onReload')),  'far:arrow-alt-circle-left blue' );
        //$this->form->addAction('BUSCAR CEP', new TAction([$this, 'onCep']), 'fa:search');
        //$this->form->addAction('BUSCAR CNPJ', new TAction([$this, 'onCNPJ']), 'fa:search');
        
        //$action_cep = new TAction(array($this,'onCep'));
        //$cep->setExitAction($action_cep);
        
        // wrap the page content
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'CadastroPessoasList'));
        $vbox->add($this->form);
        
        // add the form inside the page
        parent::add($vbox);
    }
    
    public function validate()
    {
        // assign post data before validation
        // validation exception would prevent
        // the user code to execute setData()
        $this->setData($this->getData());
        
        foreach ($this->fields as $fieldObject)
        {
            $fieldObject->validate();
        }
    }
   
   /*
    public function onSave()
    {
        try
        {
            TTransaction::open('db_isis360');
                $object = $this->form->getData('tb_CadastroPessoas');
                $this->form->validate();
          
            //$object = new Testedois;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            // Valida o CPF
            if($data->pfoupj == 'f')
            
            {                
                $cpf = new TCPFValidator;
                $cpf->validate('CPF', $data->cpf); 
                
                
            }
            
            // Valida o CNPJ
            if($data->pfoupj == 'j' )
            {
                $cnpj = new TCNPJValidator;
                $cnpj->validate('CNPJ', $data->cnpj);
            } 
                
            
                
                //var_dump($object);
                $object->store();
                $this->form->setData( $object );
                //new TMessage('info', 'Registro salvo com sucesso!');
                TToast::show('success', 'Registro SALVO com Sucesso!', 'top right', 'far:check-circle' );
            TTransaction::close();
        }
        catch (exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
        
        
    }
    public function onEdit($param)
    {
        try
        {   
            TTransaction::open('db_isis360');
            $key = $param['id'];
            $object = new tb_CadastroPessoas($key);
            $this->form->setData( $object );
            TTransaction::close();
        }
        catch (exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
        
        
    }
     
    
    /**
     * Clear form
     
    public function onClear($param)
    {
        $this->form->clear();
        
    //    $this->contacts->addHeader();
      //  $this->contacts->addDetail( new stdClass );
      //  $this->contacts->addCloneAction();
    }
    
    public static function onChangePFouPJ($param)
    {
        if ($param['pfoupj'] == 'f' )
        {        
            
            TQuickForm::showField('form_cadastro_pessoas', 'cpf');
            TQuickForm::showField('form_cadastro_pessoas', 'rg');
            TQuickForm::showField('form_cadastro_pessoas', 'nome');
            TQuickForm::showField('form_cadastro_pessoas', 'nomesocial');
            TQuickForm::showField('form_cadastro_pessoas', 'genero');
            TQuickForm::showField('form_cadastro_pessoas', 'estadocivil');
            TQuickForm::showField('form_cadastro_pessoas', 'nascimento');
            TQuickForm::hideField('form_cadastro_pessoas', 'cnpj');
            TQuickForm::hideField('form_cadastro_pessoas', 'inscricaomunicipal');
            TQuickForm::hideField('form_cadastro_pessoas', 'inscricaoestadual');
            TQuickForm::hideField('form_cadastro_pessoas', 'suframa');
            TQuickForm::hideField('form_cadastro_pessoas', 'razaosocial');
            TQuickForm::hideField('form_cadastro_pessoas', 'fantasia');
            TQuickForm::hideField('form_cadastro_pessoas', 'dataabertura');
            TQuickForm::hideField('form_cadastro_pessoas', 'naturezajuridica');
            TQuickForm::hideField('form_cadastro_pessoas', 'porte');
            TQuickForm::hideField('form_cadastro_pessoas', 'situacaocnpj');
            TQuickForm::hideField('form_cadastro_pessoas', 'observacao');
            TEntry::disableField('form_cadastro_pessoas', 'telefonecnpj');
            TEntry::disableField('form_cadastro_pessoas', 'emailcnpj');
        }
        else
        {
             TQuickForm::hideField('form_cadastro_pessoas', 'cpf');
            TQuickForm::hideField('form_cadastro_pessoas', 'rg');
            TQuickForm::hideField('form_cadastro_pessoas', 'nome');
            TQuickForm::hideField('form_cadastro_pessoas', 'nomesocial');
            TQuickForm::hideField('form_cadastro_pessoas', 'genero');
            TQuickForm::hideField('form_cadastro_pessoas', 'estadocivil');
            TQuickForm::hideField('form_cadastro_pessoas', 'nascimento');
            TQuickForm::showField('form_cadastro_pessoas', 'cnpj');
            TQuickForm::showField('form_cadastro_pessoas', 'inscricaomunicipal');
            TQuickForm::showField('form_cadastro_pessoas', 'inscricaoestadual');
            TQuickForm::showField('form_cadastro_pessoas', 'suframa');
            TQuickForm::showField('form_cadastro_pessoas', 'razaosocial');
            TQuickForm::showField('form_cadastro_pessoas', 'fantasia');
            TQuickForm::showField('form_cadastro_pessoas', 'dataabertura');
            TQuickForm::showField('form_cadastro_pessoas', 'naturezajuridica');
            TQuickForm::showField('form_cadastro_pessoas', 'porte');
            TQuickForm::showField('form_cadastro_pessoas', 'situacaocnpj');
            TQuickForm::showField('form_cadastro_pessoas', 'observacao');
            TEntry::disableField('form_cadastro_pessoas', 'telefonecnpj');
            
        }
    }
    
    
    
    
    
    
    
    public static function onChangeIEIsento($param)
    {
        if ($param['ieisento'] == 'i' )
        {        
        
            TEntry::disableField('form_cadastro_pessoas', 'inscricaoestadual');
        
            
        }
        else
        {
             TEntry::enableField('form_cadastro_pessoas', 'inscricaoestadual');
        
        
        }
    }
    
    
    
    
    
    
static function onChangeMotivo($param)
    {
         $motivo = $param['motivo'];
         
         if ($motivo == NULL)
         {
             $statuscadastro = "NOVO";
         }
         
         else 
         {
             $statuscadastro = "ATIVO";
         }
        
    
      
         
         
         $obj = new StdClass;
         $obj->statuscadastro = $statuscadastro;
         TForm::sendData('form_cadastro_pessoas', $obj);
    }
      */ 
    
    public static function onCep($param)
    {        
        $cep = $param['cep'];
        if (!empty($cep)) 
        {
            try
            {
                    $resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($param['cep']).'&formato=query_string');  
                    if(!$resultado){  
                        $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";  
                        //var_dump($resultado);
                    }  
                    parse_str($resultado, $retorno);   
                    $obj = new StdClass;
                    //$obj->cep      = $param['cep'];
                    $obj->logradouro = strtoupper( $retorno['tipo_logradouro'].' '.$retorno['logradouro']);
                    $obj->bairro   = strtoupper( $retorno['bairro']);
                    $obj->cidade   = strtoupper( $retorno['cidade']);
                    $obj->estado       = strtoupper( $retorno['uf']);
                
                    TForm::sendData('form_cadastro_pessoas', $obj);
                    //var_dump($obj);
            }
            catch (Exception $e)
            {
                new TMessage('error','CEP não encontrado');
            }
        }
        else
        {
            new TMessage('alert', 'Por favor, Informe o CEP');
        }
    } 
    
    
    public static function onCNPJ($param)
    {
        try {
             if (isset($param['cnpj']) and $param['cnpj'])
             {
                 //Joga o valor informado para uma variavel
                 $cnpj = $param['cnpj'];
                 //Deixa apenas numeros usando expressão regular   
                $cnpj  = preg_replace("/\D/","", $cnpj);
                 //efetua a consulta e joga o resultado na variavel retorno
                 $retorno = @file_get_contents('https://www.receitaws.com.br/v1/cnpj/'.urlencode($cnpj));  
                 $objeto  = json_decode($retorno);               
                 if (isset($objeto->logradouro)){
                     $obj                            = new stdClass();
                     $obj->razaosocial               = $objeto->nome;
                     $obj->fantasia               = $objeto->fantasia;
                     
                     //$obj->tipo_pessoa     = 'J';
                     $obj->logradouro                 = $objeto->logradouro;
                     $obj->numero            = $objeto->numero;
                     $obj->complemento            = $objeto->complemento;
                     $obj->bairro               = $objeto->bairro;
                     $obj->cidade             = $objeto->municipio;
                     $obj->estado                     = $objeto->uf;
                     $obj->dataabertura        = $objeto->abertura;
                     $obj->porte             = $objeto->porte;
                     $obj->naturezajuridica        = $objeto->natureza_juridica;         
                     $obj->situacaocnpj        = $objeto->situacao;
                     $obj->cep                  = $objeto->cep;
                     $obj->telefonecnpj                  = $objeto->telefone;
                     $obj->emailcnpj                  = $objeto->email;
                     $obj->observacao     = "";
                     for ($i = 0; $i < count($objeto->qsa); $i++)
                     {
                         $obj->observacao .= $objeto->qsa[$i]->qual." - ";
                         $obj->observacao .= $objeto->qsa[$i]->nome."\n";
                     }
                     for ($i = 0; $i < count($objeto->atividade_principal); $i++)
                     {
                         $obj->observacao .= "Atividade Principal ".$objeto->atividade_principal[$i]->code." - ";
                         $obj->observacao .= $objeto->atividade_principal[$i]->text."\n";
                     }
                     for ($i = 0; $i < count($objeto->atividades_secundarias); $i++)
                     {
                         $obj->observacao .= "Atividade Secundaria ".$objeto->atividades_secundarias[$i]->code." - ";
                         $obj->observacao .= $objeto->atividades_secundarias[$i]->text."\n";
                     }
                     
                     $obj->observacao .= "Natureza Juridica - ".$objeto->natureza_juridica."\n";
                     $obj->observacao .= "Capital Social - R$-".number_format($objeto->capital_social,2,',','.')."\n";
               
                      TToast::show('info', 'CNPJ Localizado na Receita Federal', 'top right', 'far:check-circle' );
                      TEntry::disableField('form_cadastro_pessoas', 'observacao');
                     TForm::sendData('form_cadastro_pessoas',$obj);
                     unset($obj);
                 }else{
                     //new TMessage('info', 'CNPJ não localizado na Receita Federal.');
                     TToast::show('error', 'CNPJ não localizado na base da Receita Federal', 'top right', 'far:check-circle' );
                 }
             }    
        }catch (Exception $e){
             new TMessage('error', '<b>Error:</b> ' . $e->getMessage());        
        }
    }     
    
    
    
}
