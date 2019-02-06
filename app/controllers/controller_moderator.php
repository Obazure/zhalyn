<?
session_start();

uses ('app/models/food');
uses ('app/models/user');
uses ('app/models/order');
uses ('app/models/navbar');

class Controller_Moderator extends Controller {
	
	private $user;
	
	private $mUser;
	private $mFood;
	private $mOrder;
	
	private function checkSecure(){
		$this->mUser = User::getInstance();
		$this->mFood = Food::getInstance();
		$this->mOrder = Order::getInstance();
		if ($this->mUser->checkLoginStatus()==false){
			Route::Redirect(1,'Укажите ваше имя и секретное слово.', '/');
		}
		$this->user = $this->mUser->getUser();
		if ($this->user['role']!=1)
			Route::Redirect(1,'Запрашиваемая страница доступка только для модераторов','/');
		
		$this->data['modmenu'] = NavBar::moderatorMenu();
			
	
	}
	
	function action_index(){
		$users = User::getInstance();
		$this->user = $users->getUser();
		if ($this->user['role']==1)
			Route::Redirect(0,'','/moderator/account');
		Route::Redirect(0,'','/user/account');
	}
	
	
	
	function action_account(){
		$this->checkSecure();
		
		$this->data['username'] = $this->user['name'];
		
		$orders = $this->mOrder->getOrdersForToday();
		
		
		$orders = $this->mOrder->getOrdersMinusExtra($orders);
		$this->data['extra'] = $this->mOrder->getExtra();
		
		$this->data['order'] = $orders;
		
		$this->data['time'] = date("d-m-Y",strtotime($orders[0]['timestamp']));
		
		$this->data['formpath'] = '/moderator/cancelorder';
		
		$this->data['title']='Кабинет модератора';
		
		if($_POST['_params']['print']=='on')
		{
			$this->view->generate('view_moderatorroomempty.php', 'layout_empty.php', $this->data);
			exit();
		}
		$this->view->generate('view_moderatorroom.php', 'layout_default.php', $this->data);
		
	}
	
	function action_cancelorder(){
		$this->checkSecure();
		
		$this->mOrder->clearOrder($_POST['radio']);
		Route::Redirect(0,'Отменено.');
	}
	
	function action_todayfood(){
		$this->checkSecure();
		$this->data['title']='Сегодня на обед';
		
		$this->data['formpath']='/moderator/todayfoodhandler';
		
		$this->data['food'] = $this->mFood->getSetterFoodForToday();
		
		$this->view->generate('view_moderatorroomfoodfortoday.php', 'layout_default.php', $this->data);
	}
	function action_todayfoodhandler(){
		$this->checkSecure();
		$ids = array_keys($_POST, "on");
		$this->mFood->setFoodForToday($ids);
		Route::Redirect(0,'Сохранено.');
	}
	
	function action_allfood(){
		$this->checkSecure();
		$this->data['title']='Добавить/Изменить/Удалить блюда';
		
		$this->data['food'] = $this->mFood->getAll();
		
		$this->data['foodtypes'] = $this->mFood->getTypes();
		
		if($_POST['_params']['edit']!=0 and !empty($_POST['_params']['edit']))
			$this->data['formfood'] = $this->mFood->getFood($_POST['_params']['edit']);
		
		$this->data['formpath'] = '/moderator/allfoodhandler';
			
		$this->view->generate('view_moderatorallfood.php', 'layout_default.php', $this->data);
		
	}
	
	function action_allfoodhandler(){
		$this->checkSecure();
		
		$_POST['foodid']+=0;
		if (!empty($_POST['name']) and
			!empty($_POST['cost']) and
			!empty($_POST['foodtype']))
		{
			$id = $_POST['foodid'];
			$name = $_POST['name'];
			$cost = $_POST['cost'];
			$type = $_POST['foodtype'];
			
			if ($_POST['foodid']!=0){
				$this->mFood->update($id,$name,$cost,$type);
				Route::Redirect(0,'Обновено.','/moderator/allfood/');
			}else{
				$this->mFood->add($name,$cost,$type);
				Route::Redirect(0,'Добавлено.','/moderator/allfood/');
			}
			exit();
			Route::Redirect(1,'Произошла ошибка при пополнении.');
		}
		
		
	}
	
	function action_usermoney(){
		$this->checkSecure();
		$this->data['title']='Пополнить счет';
		$this->data['users'] = $this->mUser->getUsersList();
		$this->data['usersmoney']=$this->mUser->getAccounts();
		$this->data['formpath']='/moderator/addmoneyhandler';
		$this->view->generate('view_moderatorroomaddmoney.php', 'layout_default.php', $this->data);
	}
	
	function action_addmoneyhandler(){
		$this->checkSecure();
		$_POST['user']+=0;$_POST['money']+=0;
		$tmp = $this->mUser->addMoney($_POST['user'],$_POST['money']);
		if ($tmp==true){
			$_SESSION['response'] = 'Пополнено на '.$_POST['money'].'₸';
			Route::Redirect(0,'Пополнено на '.$_POST['money'].'₸');
		} else {
			$_SESSION['response'] = 'Пополнено на '.$_POST['money'].'₸';
			Route::Redirect(1,'Произошла ошибка при пополнении.');
		}
	}
	
	
	function action_food(){
		$this->checkSecure();
		
		
		
		/*
		 * Тут всякая фигня Администратора
		 Блюда
		 Добавить, Изменить, Удалить из общего списка
		 */
		$this->mFood->addNew();
		$this->mFood->update();
		$this->mFood->delete();
			
	}
	
	
	/*
	 Добавить, Изменить, Удалить на обед на сегодня
	 Заказы
	 Подтвердить и удалить заказы
	 Печать
	 money
	 add money to user account
	 */
}
