<?php

    namespace Service;

    use InvalidArgumentException;
    use Respository\UsuariosRepository;
    use Util\ConstantesGenericasUtil;

    class UsuariosService
    {
        public const TABELA = 'usuarios';
        public const RECURSOS_GET = ['listar'];
        public const RECURSOS_DELETE = ['deletar'];
        public const RECURSOS_POST = ['cadastrar'];
        public const RECURSOS_PUT = ['atualizar'];

        private array $dados;

        private array $dadosCorpoRequest = [];

        private object $UsuariosRepository;
        //o construct é quem constroi a formatação dos dados 
        public function __construct($dados = [])
        {
            $this->dados = $dados;
            $this->UsuariosRepository = new UsuariosRepository();
        }
        //rota para listar usuarios
        public function validarGet()
        {
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if (in_array($recurso, self::RECURSOS_GET, strict)) {
                $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
            }else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }

            if ($retorno === null){
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }

            return $retorno;
        }
        //rota para deletar usuarios
        public function validarDelete()
        {
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if (in_array($recurso, self::RECURSOS_DELETE, strict)) {
                if ($this->dados['id'] > 0){
                    $retorno = $this->$recurso();
                }else{
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
                }
            }else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }

            if ($retorno === null){
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }

            return $retorno;
        }
        //rota para enviar usuarios para o banco
        public function validarPost()
        {
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if (in_array($recurso, self::RECURSOS_POST, strict)) {
                $retorno = $this->$recurso();
            }else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }

            if ($retorno === null){
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }

            return $retorno;
        }
        //rota para atualizar usuarios do banco
        public function validarPut()
        {
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if (in_array($recurso, self::RECURSOS_PUT, strict)) {
                if ($this->dados['id'] > 0){
                    $retorno = $this->$recurso();
                }else{
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
                }
            }else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }

            if ($retorno === null){
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }

            return $retorno;
        }
        //aqui esta pegando dados enviados e cadastrando
        public function setDadosCorpoRequest($dadosRequest)
        {
            $this->dadosCorpoRequest = $dadosRequest;
        }
        private function cadastrar()
        {
            [$login, $senha] = [$this->dadosCorpoRequest['login'], $this->dadosCorpoRequest['senha']];
            
            if ($login && $senha){
                if ($this->UsuariosRepository->insertUser($login, $senha) > 0)
                {
                    $idInserido = $this->UsuariosRepository->getMySQL()->getDB()->lastInsertId();
                    $this->UsuariosRepository->getMySQL()->getDb()->commit();
                    return ['id_inserido' => $idInserido];
                }

                $this->UsuariosRepository->getMySQL()->getDb()->rollBack();

                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
        }
        //metodo que pega usuarios por id
        private function getOneByKey()
        {
            return $this->UsuariosRepository->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
        }
        //metodo que pega todos usuarios do banco
        private function listar()
        {
            return $this->UsuariosRepository->getMySQL()->getAll(self::TABELA);
        }
        //metodo que deleta usuarios do banco
        private function deletar()
        {
            return $this->UsuariosRepository->getMySQL()->delete(self::TABELA, $this->dados['id']);
        }
        //atualiza meus dados do banco
        private function atualizar()
        {
            if ($this->UsuariosRepository->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0)
            {
                $this->UsuariosRepository->getMySQL()->getDb()->commit();
                return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
            }
            $this->UsuariosRepository->getMySQL()->getDb()->rollBack();
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
        }
    }