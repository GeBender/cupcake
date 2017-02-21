<?php
/**
 * (c) CupcakePHP: The Rapid and Tasty Development Framework.
 *
 * PHP version 5.5.12
 *
 * @author  Ge Bender <gesianbender@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version GIT: <git_id>
 * @link    http://cupcake.simplesys.com.br
 */
namespace Cupcake;

use Apps\Logistick\DAO\LogAssinantesDAO;
use Apps\Logistick\DAO\UsuariosDAO;

class Logs
{

    public static $app;
    public static $dao;
    public static $usuarioDao;
    public static $assinanteDao;

    public static function setApp($app)
    {
        self::$app = $app;
    }

    public static function atividadeAssinante($atividade, $item = null, $obs = null, $assinanteId = null)
    {
        self::$dao = new LogAssinantesDAO(self::$app, 'LogAssinantes');
        self::$usuarioDao = new UsuariosDAO(self::$app, 'Usuarios');
        self::$assinanteDao = new DAO(self::$app, 'Assinantes');

        self::atividadeAssinanteHora($atividade, $item, $obs, $assinanteId);
        self::atividadeAssinanteDia($atividade, $item, $obs, $assinanteId);
        self::atividadeAssinanteSemana($atividade, $item, $obs, $assinanteId);
        self::atividadeAssinanteMes($atividade, $item, $obs, $assinanteId);
        self::atividadeAssinanteAno($atividade, $item, $obs, $assinanteId);
    }

    public static function atividadeAssinanteHora($atividade, $item, $obs, $assinanteId)
    {
        self::logAtividadeAssinante($atividade, 'hora', (int) date('G'), $item, $obs, $assinanteId);
    }

    public static function atividadeAssinanteDia($atividade, $item, $obs, $assinanteId)
    {
        self::logAtividadeAssinante($atividade, 'dia', (int) date('z'), $item, $obs, $assinanteId);
    }

    public static function atividadeAssinanteSemana($atividade, $item, $obs, $assinanteId)
    {
        self::logAtividadeAssinante($atividade, 'semana', (int) date('N'), $item, $obs, $assinanteId);
    }

    public static function atividadeAssinanteMes($atividade, $item, $obs, $assinanteId)
    {
        self::logAtividadeAssinante($atividade, 'mes', (int) date('j'), $item, $obs, $assinanteId);
    }

    public static function atividadeAssinanteAno($atividade, $item, $obs, $assinanteId)
    {
        self::logAtividadeAssinante($atividade, 'ano', (int) date('Y'), $item, $obs, $assinanteId);
    }

    public static function logAtividadeAssinante($atividade, $tipo, $tempo, $item, $obs, $assinanteId)
    {
        $criteria = [
            'atividade' => $atividade,
            'tipo' => $tipo,
            'tempo' => $tempo,
        ];

        if ($item) {
            $criteria['item'] = $item;
        }

        if ($obs) {
            $criteria['observacoes'] = $obs;
        }

        $log = self::$dao->findBy($criteria, ['id' => 'desc'], 1);
        if (count($log)) {
            $log = $log[0];
        } else {
            $log = self::newLogAtividadeAssinante($atividade, $tipo, $tempo, $item, $obs, $assinanteId);
        }

        $log->incrementa();
        $log->marcaDataHora();

        self::$dao->salvar($log);
    }

    public static function newLogAtividadeAssinante($atividade, $tipo, $tempo, $item, $obs, $assinanteId)
    {
        $log = new \LogAssinantes();

        $log->setAtividade($atividade);
        $log->setTipo($tipo);
        $log->setTempo($tempo);
        $log->setItem($item);
        $log->setObservacoes($obs);
        $log->setQuantidade(0);
        $log->setQuantidade(0);

        if ($assinanteId) {
            $Assinante = self::$assinanteDao->find($assinanteId);
            $log->setAssinante($Assinante);
        }

        $log->setUsuario(self::$app['Auth']->getUsuario(self::$usuarioDao));

        return $log;
    }
}
