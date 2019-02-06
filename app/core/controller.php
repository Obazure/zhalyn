<?

session_start();


uses ('app/models/navbar');

class Controller {
	public $model;
	public $view;
	protected $data;
	
	function __construct()
	{
		$this->data['navbar']=NavBar::getNavBar();
		$this->view = new View();
		
		
	}
	// действие (action), вызываемое по умолчанию
	function action_index()
	{
		// todo	
	}
}