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
namespace Cupcake\helpers;

use FileUpload\Validator\Simple;
use FileUpload\PathResolver\Simple as PathResolverSimple;
use FileUpload\Validator\MimeTypeValidator;
use FileUpload\Validator\SizeValidator;
use FileUpload\FileUpload;
use Cupcake\Flash;

class JQFileUpload extends \Cupcake\Helper
{

    public $validator = [];
    public $mimeTypeValidator;
    public $sizeValidator;
    public $pathresolver;
    public $filesystem;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->simpleValidator(1024 * 1024, ['image/png', 'image/jpeg']);
        $this->setSimplePathresolver($app['GPS']->getUploadFolder());
        $this->setSimpleFileSystem();
    }

    public function upload($field)
    {
        if (@$_FILES[$field]) {
            $fileupload = new FileUpload($_FILES[$field], $_SERVER);

            $this->pathresolver = new PathResolverSimple(dirname(dirname(dirname(dirname(__DIR__)))).'/Apps/'.$this->app['route']['appName'].'/uploads');
            $fileupload->setPathResolver($this->pathresolver);
            $result = $fileupload->setFileSystem($this->filesystem);

            foreach ($this->validator as $validator) {
                $fileupload->addValidator($validator);
            }

            $result = $fileupload->processAll();
            $this->checkForErrors($result);

            return $result;
        }
        return false;
    }

    public function checkForErrors($result)
    {
        $erros = ['Tipo de arquivo não permitido. Envie PNG ou JPG', 'Imagem muito grande. Tamanho máximo: 1 mega.'];
        foreach ($result[0] as $upload) {
            if ($upload->error !== 0) {
                Flash::alert($erros[$upload->error_code]);
            }
        }
    }

    /**
     * @param int $size
     * @param array $mimeTypes
     */
    public function simpleValidator($size, $mimeTypes)
    {
        $this->validator[] = new Simple($size, $mimeTypes);
    }


    /**

     * @param array $mimeTypes
     */
    public function mimeTypeValidator($mimeTypes)
    {
        $this->validator[] = new MimeTypeValidator($mimeTypes);
    }


    /**
     * @param string $max
     * @param string $min
     */
    public function sizeValidator($max, $min)
    {
        $this->validator[] = new SizeValidator($max, $min);
    }

    /**
     * @param string $path
     */
    public function setSimplePathResolver($path)
    {
        $this->pathresolver = new \FileUpload\PathResolver\Simple($path);
    }

    public function setSimpleFileSystem()
    {
        $this->filesystem = new \FileUpload\FileSystem\Simple();
    }

}