<?

session_start();


uses ('app/models/food');
uses ('app/models/extracost');
uses ('app/models/user');

class Controller_Food extends Controller{
	
	function action_index(){
		$this->data['title']='Блюда';
		
		$food = Food::getInstance();
		$extracosts = ExtraCosts::getInstance();
		$this->data['food'] = $food->getAll();
		$this->data['extracosts'] = $extracosts->getAll();
		
		
		$this->view->generate('view_allfood.php', 'layout_default.php',$this->data);
	}
	function action_select(){
		$user = User::getInstance();
		if ($user->checkLoginStatus()==false){
			Route::Redirect(1,'Укажите ваше имя и секретное слово.', '/');
		}
		//$this->data['name'] = $user['name'];
		
		$this->systemTimeCheck();
			
			/*
			 * TODO Выбрать меню на сегодня
			 *  проверить галочки
			 */
		$food = Food::getInstance();
		$this->data['food'] = $food->getFoodForToday();
		
		//$this->data['food'] = $food->getCheckersForFood($this->data['food'],$user->checkLoginStatus('user_id'));
		
		$extracosts = ExtraCosts::getInstance();
		$this->data['extracosts'] = $extracosts->getAll();
		
		// TODO проверка выбранных галочек
		
		$this->data['title']='Блюда на сегодня';
		$this->data['formpath']='/food/selectcost';
		$this->view->generate('view_selectfood.php', 'layout_default.php',$this->data);
	}
	
	function action_selectcost(){
		$user = User::getInstance();
		$food = Food::getInstance();
		
		$extracosts = ExtraCosts::getInstance();
		
		if ($user->checkLoginStatus()==false){
			Route::Redirect(1,'Укажите ваше имя и секретное слово.', '/');
		}
		$this->systemTimeCheck();
		
		$ids = array_keys($_POST, "on");
		$list = explode(',', $_POST['foodlist']);
		
		for ($i=0,$size=sizeof($ids);$i<$size;$i++){
			$isexist = $food->checkFoodInMenu($ids[$i]);
			if(!(in_array ($ids[$i],$list) and $isexist))
			{
				$ids[$i]=0;
			}
		}
		
		$this->data['food'] = $food->getSelectedFoodForTodayWithCost($ids);
		
		$this->data['cost'] = 0;
		for ($i=0,$size=sizeof($this->data['food']);$i<$size;$i++){
			$this->data['cost'] += $this->data['food'][$i]['cost']; 
		}
		$this->data['extracosts'] = $extracosts->getAll();
		for ($i=0,$size=sizeof($this->data['extracosts']);$i<$size;$i++){
			$this->data['cost'] += $this->data['extracosts'][$i]['cost'];
		}
		
		$this->data['currentAccount'] = $user->getCurrentAccount();
		
		$this->data['extramsg'] = ($this->data['currentAccount'] < $this->data['cost']) 
				?'Суммы на балансе не достаточно для списания.<br/>Незабудьте оплатить обед менеджеру.'
				:'';
		
		$_SESSION['tmp_cost'] = $this->data['cost'];
		$_SESSION['tmp_selected_food'] = $this->data['food'];
		$_SESSION['tmp_hash'] = md5($_SESSION['tmp_cost'].$_SESSION['tmp_selected_food'].$_SESSION['session_hash']);
		
		
		$this->data['title']='Подтвердите заказ';
		$this->data['formpath']='/food/costsave';
		$this->view->generate('view_selectfoodcost.php', 'layout_default.php',$this->data);
	}
	
	function action_costsave(){
		$user = User::getInstance();
		$food = Food::getInstance();
		
		if ($user->checkLoginStatus()==false){
			Route::Redirect(1,'Укажите ваше имя и секретное слово.', '/');
		}
		
		
		if (md5($_SESSION['tmp_cost'].$_SESSION['tmp_selected_food'].$_SESSION['session_hash']) == $_SESSION['tmp_hash']){
			$food->saveOrder($user->getUser(),$_SESSION['tmp_selected_food'], $_SESSION['tmp_cost']);
			
			unset($_SESSION['tmp_cost']);
			unset($_SESSION['tmp_selected_food']);
			unset($_SESSION['tmp_hash']);
			Route::Redirect(0,'Заказ сохранен.','/user/room');
		}else Route::Redirect(1,'Ошибка запроса.','/');
	}
	
	
	private function systemTimeCheck(){
		$d = gmdate('H')+6;
		if($d>9 or $d<11)
			Route::Redirect(2,'Функция заказа действительна только между 9:00 и 11:00.','/');
	}
}
