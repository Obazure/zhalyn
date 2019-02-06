<?

session_start();

class User extends Model {
	
	private static $_singleton;
	
	public static function getInstance() {
		if(!self::$_singleton) {
			self::$_singleton = new User();
		}
		return self::$_singleton;
	}
	
	protected $user = NULL;
	
	public function checkLoginStatus($what_to_return=NULL)
	{
		
		if (!empty($_SESSION['session_hash']))
		{
			$this->db->execute_query("DELETE FROM `users_session_hash` WHERE timestamp < (NOW() - INTERVAL 1 HOUR)");
				
			$user_hash_face = $this->db->select_query('users_session_hash',' id as hash_id, user as user_id ', ['hash'=> $_SESSION['session_hash']],'ORDER BY timestamp DESC LIMIT 1');
			$user_hash_face['user_id']+=0;
			if ($user_hash_face['user_id']!=0) {
				if (!empty($what_to_return)) 
				{
					return $user_hash_face[$what_to_return];
				}
					else return true;
			}
		}
		return false;
	}
	
	public function getUser($name='',$password=''){
		/*
		 * Проверяем входные данные, чтобы они не были пустыми.
		 */
		$id = $this->checkLoginStatus('user_id');
		

		$name = $this->makeCharsSecure(mb_strtolower($name));
		$password = $this->encryptPass($name, $this->makeCharsSecure($password));


if($id!=0) {
			$this->user = $this->db->select_query('users',' * ',['id'=>$id], 'LIMIT 1');
			return $this->user;
		}else {
		

		if (empty($name) or empty($password)) {Route::Redirect(1,'Введены пустые данные!');}
		
		/*
		 * ПРоверяем существует ли пользователь. Если нет, то создаем.
		 */
		$this->user = $this->db->select_query('users',' * ',['name'=>$name], 'LIMIT 1');
		
		if (!empty($this->user['password']) and $this->user['password']!=$password) Route::Redirect(1,'Неверные логин или пароль!');
		
		if($this->user==false) {
			$this->user['id'] = $this->db->insert_query('users',['name' => $name,'password' => $password]);
			$this->user['name'] = $name;
			$this->user['password'] = $password;
			$this->user['role'] = 0;
		}
		
		/*
		 * По пользователю создаем сессию
		 */
		$_SESSION['session_hash'] = md5($this->user['name'].$this->user['password'].time());
		$this->db->insert_query('users_session_hash',['hash' => $_SESSION['session_hash'],'user' => $this->user['id']]);
		
		return $this->user;
		}
	}
	
	public function getCurrentAccount(){
		$tmp = array_values ($this->db->select_query('users_payment_store', 'SUM(amount)', ['user'=>$this->checkLoginStatus('user_id')]));
		$this->user['account'] = $tmp[0] + 0;
		return $this->user['account'];
	}
	
	public function addMoney($user,$money){
		$tmp = $this->db->insert_query('users_payment_store',['user' => $user,'amount' => $money]);
		if (!empty($tmp) and is_numeric($tmp)) return true;
		else return false;	
	}
	
	public function getUsersList(){
		$rtn = $this->db->select_query('users','id,name','');
		return $rtn;
	}
	public function getAccounts(){
		$rtn = $this->db->execute_select_query('users_payment_store','SUM(amount), `users`.name ','INNER JOIN  `users` ON  `users_payment_store`.`user` =  `users`.`id` GROUP BY `users`.`id` ORDER BY `users`.`name` DESC');
		return $rtn;
	}
	
	private function encryptPass($login,$pass)
	{
		if (!empty($login) and !empty($pass)) return md5(md5($login.'uws'.$pass).$pass);
		return false;
	}
}
