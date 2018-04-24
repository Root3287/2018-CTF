<?php namespace Root3287\classes;
class User{
	private $_db, $_data, $_isLogin, $_sessionName, $_cookieName, $_admLoggedIn;
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('session/cookie_name');
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)){
					$this->_isLogin = true;
				}else{
					//Log out
				}
			}
		}else{
			$this->find($user);
		}
	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception("There was a problem updating");		
		}
	}
	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields)){	
			throw new Exception('There was an error adding the user! Contact an administrator!');
		}
	}
	public function find($user = null){
		if($user){
			$field =  (is_numeric($user))? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
	}
	public function login($username = null, $pass = null, $remember = false){
		if(!$username && !$pass && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		}else{
			$user = $this->find($username);
			if($user){
				if($this->_data->password === Hash::make($pass, $this->_data->salt)){
					Session::put($this->_sessionName, $this->_data->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('user_session', array('user_id','=',$this->data()->id));
						if(!$hashCheck->count()){
							$this->_db->insert('user_session',array(
									'user_id' => $this->_data->id,
									'hash' => $hash
							));
						}else{
							$hash = $hashCheck->first()->hash;
						}
						Cookies::put($this->_cookieName, $hash, config::get('remember/expiry'));
					}
					return true;
				}
			}
		}
		return false;	
	}
	public function hasPermission($key) {
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));
		if ($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);
			if (isset($permissions[$key]) && $permissions[$key] == true) {
				return true;
			}
		}
		return false;
	}
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}
	public function getGroupId(){
		return $this->_data->group;
	}
	public function data(){
		return $this->_data;
	}
	public function isLoggedIn(){
		return $this->_isLogin;
	}
	public function getAvatarURL($size = '32'){
		return "https://gravatar.com/avatar/".md5($this->data()->email)."?d=mm&s={$size}&r=pg";
	}
	public function logout() {
		$userAdm = new AdminUser();
		if($userAdm->isLoggedIn()){
			$userAdm->logout();
		}
		$this->_db->delete('user_session', array('user_id', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookies::delete($this->_cookieName);
	}
}