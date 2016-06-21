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

class DataTable extends \Cupcake\Helper
{

    public $className = 'datatable';

    public $order = 0;

    public $paginate = true;

    public $search = true;

    public $info = 'Mostrando do _START_º ao _END_º de _TOTAL_ registros';

    public $direction = 'asc';

    public $pageLength = 10;

    public $noOrderables = false;


    public function __construct($app)
    {
        parent::__construct($app);

    }


    public function className($className)
    {
        $this->className = $className;
        return $this;

    }


    public function order($order)
    {
        $this->order = $order;
        return $this;

    }


    public function noPaginate()
    {
        $this->paginate = false;
        return $this;

    }


    public function noSearch()
    {
        $this->search = false;
        return $this;

    }


    public function noInfo()
    {
        $this->info = false;
        return $this;

    }


    public function direction($direction)
    {
        $this->direction = $direction;
        return $this;

    }


    public function pageLength($pageLength)
    {
        $this->pageLength = $pageLength;
        return $this;

    }


    public function noOrderables($noOrderables)
    {
        (is_array($noOrderables) === false) ? $noOrderables = array($noOrderables) : false;
        $this->noOrderables = $noOrderables;
        return $this;

    }


    public function desc()
    {
        $this->direction = 'desc';
        return $this;

    }


    public function asc()
    {
        $this->direction = 'asc';
        return $this;

    }


    public function get()
    {
        $this->addExtraHeaderA('<link rel="stylesheet" type="text/css" href="' . $this->getCupcakeAsset() . 'datatable/css/jquery.dataTables.css">');
        $this->addExtraFooter('<script type="text/javascript" src="' . $this->getCupcakeAsset() . 'datatable/js/jquery.dataTables.min.js"></script>');
        $this->addExtraFooter('<script type="text/javascript">
                $(document).ready(function() {
                    ' . $this->dataTableStart() . '
               });
               </script>');

    }


    public function dataTableStart()
    {
        $command = '$(\'.'.$this->className.'\').dataTable({
                        "order": [' . $this->order . ', \''.$this->direction.'\'],
                        "pageLength": ' . $this->pageLength . ',';

        if ($this->paginate === false) {
            $command .= ' "bPaginate": false, ';
        }
        if ($this->paginate === false) {
            $command .= ' "searching": false, ';
        }

        $command .= '
                		"sDom": "<\'row-fluid no-print\'<\'span6\'l><\'span6\'f>r>t<\'row-fluid\'<\'left\'i><\'right no-print\'p>>",
                        "oLanguage": {
                		  "sLengthMenu": "_MENU_ Registros por página",
                		  "sSearch": "Buscar: ",
                		  "sSortAscending": "Ordernar de forma crescente",
                		  "sSortDescending": "Ordernar de forma decrescente",
                		  "sEmptyTable": "Nada por aqui",
                          "sInfo": "'.$this->info.'",
                          "sInfoEmpty": "",
                		  "sInfoFiltered": ", filtrado(s) dentre _MAX_ registros",
                		  "sDecimal": ",",
                		  "sThousands": ".",
                		  "sLoadingRecords": "Carregando...",
                		  "sProcessing": "Carregando...",
                		  "sZeroRecords": "Nenhum registro encontrado para esta busca",
                          "oPaginate": {
                              "sFirst": "Primeiro",
                    		  "sLast": "Último",
                    		  "sNext": "Próximo",
                    		  "sPrevious": "Anterior",
                          },
                		},';
        if ($this->noOrderables !== false) {
            $command .= '"columnDefs": [';
            foreach ($this->noOrderables as $noOrderable) {
                $command .= '{ "orderable": false, "targets": ' . $noOrderable . ' },';
            }

            $command .= ']';
        }

        $command .= '} );';

        return $command;

    }


}