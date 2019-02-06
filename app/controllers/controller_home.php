<?

session_start();


uses ('app/models/extracost');
uses ('app/models/user');

class Controller_Home extends Controller
{
		
	function action_index()
	{	
		$this->data['title']='Заказать';
		$this->data['showlogo']=true;
		$user = User::getInstance();
		$this->data['loginstatus'] = $user->checkLoginStatus();
		$this->data['formpath']='/user/foodselect';
		$this->view->generate('view_home.php', 'layout_default.php', $this->data);
	}
	
	function action_rules()
	{
		$this->data['title']='Правила';
		
		$extracosts = ExtraCosts::getInstance();
		$this->data['extracosts'] = $extracosts->getAll();
		
		$this->view->generate('view_funrules.php', 'layout_default.php', $this->data);
	}

}