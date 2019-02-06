<?

session_start();

uses ('app/models/food');
uses ('app/models/user');
uses ('app/models/order');

class Controller_User extends Controller{
	
	protected $user;
	
	function action_index(){ Route::Redirect(0,'','/');	}
	
	function  action_signin(){
		$this->data['title']='Войти';
		$this->data['formpath']='/user/signinhandler';
		$this->view->generate('view_signinform.php', 'layout_default.php', $this->data);
	}
	
	function  action_signinhandler(){
		$users = User::getInstance();
		if ($this->user = $users->getUser($_POST['name'],$_POST['password']))
		Route::Redirect(0,'Добро пожаловать, '.$this->user['name'].'!','/user/room');
	}
	
	function action_room(){
		$users = User::getInstance();
		$this->user = $users->getUser();
		
		if ($this->user['role']==1)
			Route::Redirect(0,'','/moderator');
		Route::Redirect(0,'','/user/account');
	}
	
	function action_foodselect(){
		$users = User::getInstance();
		$this->user = $users->getUser($_POST['name'],$_POST['password']);
		Route::Redirect(0,'','/food/select');
	}
	
	
	function action_logout(){
		unset($_SESSION['session_hash']);
		$_SESSION = [];
		session_destroy();
		Route::Redirect(0,'','/');
	}
	
	function action_account(){
		$user = User::getInstance();
		$food = Food::getInstance();
		$order = Order::getInstance();
		
		if ($user->checkLoginStatus()==false){
			Route::Redirect(1,'Укажите ваше имя и секретное слово.', '/');
		}
		
		$this->data['title']='Кабинет Пользователя';
		$this->data['currentpayment'] = $user->getCurrentAccount();
		
		$currentOrder = $_POST['_params']['order']+0;
		$this->user = $user->getUser();
		$this->data['username'] = $this->user['name'];
		$this->order = $order->getPayment($this->user['id'],$currentOrder);
		if (empty($this->order)) {
			if ($currentOrder!=0) Route::Redirect(0,'','/room/user');
			$this->view->generate('view_userroomemptyorder.php', 'layout_default.php', $this->data);
		} else {
			$this->data['time'] = date("d-m-Y",strtotime($this->order['timestamp']));
			$this->data['cost'] = $this->order['amount'];
			$this->data['food'] = $food->getOrderedFood($this->order['hash']);
			$this->data['prevorder'] = $order->getPrevOrder($this->user['id'],$currentOrder);
			$this->data['nextorder'] = $order->getNextOrder($currentOrder);
			/*
				getOrderPaginator();
				*/
			/*
				* История заказов по дням с кнопочками влево и вправо
				* Вывести счет
				*/
			$this->view->generate('view_userroom.php', 'layout_default.php', $this->data);
		}
	}
	
}