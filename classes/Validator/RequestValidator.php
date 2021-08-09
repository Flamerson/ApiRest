<?php

    namespace Validator;

    use InvalidArgumentException;
    use Respository\TokensAutorizadosRepository;
    use Util\ConstantesGenericasUtil;
    use Util\JsonUtil;
    use Service\UsuariosService;

    class RequestValidator
    {

        private array $request;
        private array $dadosRequest = [];
        private object $TokensAutorizadosRepository;

        const GET = 'GET';
        const DELETE = 'DELETE';
        const USUARIOS = 'USUARIOS';

        public function __construct($request)
        {
            $this->TokensAutorizadosRepository = new TokensAutorizadosRepository();
            $this->request = $request;
        }
        public function processarRequest()
        {
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

            $this->request['metodo'] == 'POST';

            if (in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)){
                $retorno = $this->direcionarRequest();
            }
            return $retorno;
        }
        private function direcionarRequest()
        {
            if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE){
                $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
            }
            $this->TokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);
            $metodo = $this->request['metodo'];
            return $this->$metodo();
        }

        private function get()
        {
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET, strict)){
                switch ($this->request['rota']){
                    case self::USUARIOS:
                        $UsuariosService = new UsuariosService($this->request);
                        $retorno = $UsuariosService->validarGet();
                        break;
                    default:
                        throw new \InvalidArgumentException(ConstanteGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }
            return $retorno;
        }
 
        private function delete()
        {
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE, strict)){
                switch ($this->request['rota']){
                    case self::USUARIOS:
                        $UsuariosService = new UsuariosService($this->request);
                        $retorno = $UsuariosService->validarDelete();
                        break;
                    default:
                        throw new \InvalidArgumentException(ConstanteGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }
            return $retorno;
        }
        
        private function post()
        {
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST, strict)){
                switch ($this->request['rota']){
                    case self::USUARIOS:
                        $UsuariosService = new UsuariosService($this->request);
                        $UsuariosService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $UsuariosService->validarPost();
                        break;
                    default:
                        throw new \InvalidArgumentException(ConstanteGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }
            return $retorno;
        }

        private function put()
        {
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT, strict)){
                switch ($this->request['rota']){
                    case self::USUARIOS:
                        $UsuariosService = new UsuariosService($this->request);
                        $UsuariosService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $UsuariosService->validarPut();
                        break;
                    default:
                        throw new \InvalidArgumentException(ConstanteGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }
            return $retorno;
        }
    }