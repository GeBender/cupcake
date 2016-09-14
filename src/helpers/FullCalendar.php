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

use Cupcake\Helper;

class FullCalendar extends \Cupcake\Helper
{

    public $events = array();

    public $putIn = '#calendar';

    public $ajaxFeed = '/feed-calendar';

    public $start;

    public function __construct($app)
    {
    	parent::__construct($app);

    	$this->start = date('Y-m-d');

    	$this->addExtraHeaderB('<link rel="stylesheet" type="text/css" href="' . $this->getCupcakeAsset() . 'fullcalendar/fullcalendar.css">');
    	$this->addExtraFooter('<script type="text/javascript" src="' . $this->getCupcakeAsset() . 'fullcalendar/lib/moment.min.js"></script>');
    	$this->addExtraFooter('<script type="text/javascript" src="' . $this->getCupcakeAsset() . 'fullcalendar/fullcalendar.min.js"></script>');
    	$this->addExtraFooter('<script type="text/javascript" src="' . $this->getCupcakeAsset() . 'fullcalendar/lang-all.js"></script>');
    }

    public function start($data)
    {
    	$this->start = $data;
    }

    public function pUtIn($v)
    {
    	$this->putIn = $v;
    }

    public function setAjaxFeed($v)
    {
    	$this->ajaxFeed = $v;
    }

    public function addEvent($event=[])
    {
    	if($event['start'] instanceof \DateTime) {
        	$event['start'] = $event['start']->format('Y-m-d');
    	}
        $this->events[] = $event;
    }

    public function getJsonEvents()
    {
        return json_encode($this->events);
    }

    public function load()
    {
    	$this->addExtraFooter('<script type="text/javascript">
                $(document).ready(function() {
                    ' . $this->scriptLoad() . '
               });
               </script>');
    }

    public function scriptLoad()
    {
    	return "$('".$this->putIn."').fullCalendar({
			lang: 'pt-br',
		     header: {
		         left: 'prev,next today',
		         center: 'title',
		         right: 'month,basicWeek,basicDay'
		     },

			defaultDate: '". $this->start."',
			editable: false,
			nowIndicator: true,
			eventLimit: true,
	        events: {
	            url: '".$this->ajaxFeed."',
	            type: 'GET',
	            error: function() {
	                alert('there was an error while fetching events!');
	            }
	        },
	        dayClick: function(date, jsEvent, view) {
	        	$('#calendar').fullCalendar('changeView', 'basicDay');
	        	$('#calendar').fullCalendar('gotoDate',date);

	        }
		});
	    $('#panel-map').height($('#panel-calendar').height());";
    }

}