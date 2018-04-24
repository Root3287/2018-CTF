<?php namespace Root3287\classes;
class AdminUser{
	private $_user, $_db, $_loggedIn = false;

	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_user = new User($user);
		if(!$user){
			if(Session::exists(Config::get('session/admin_session_name'))){
				$userHash = Session::get(Config::get('session/admin_session_name'));

				if($this->_user->find($userHash)){
					$this->_loggedIn = true;
				}else{
					//Log out
				}
			}
		}else{
			$this->_user->find($user);
		}
	}

	public function login($username = null, $pass = null, $remember = false){
		if(!$username && !$pass && $this->user()->exists()){
			Session::put(Config::get('session/admin_session_name'), $this->user()->data()->id);
		}else{
			$user = $this->user()->find($username);
			if($user){
				if($this->user()->data()->password === Hash::make($pass, $this->user()->data()->salt)){
					Session::put(Config::get('session/admin_session_name'), $this->user()->data()->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('adm_user_session', array('user_id','=',$this->user()->data()->id));
						if(!$hashCheck->count()){
							$this->_db->insert('adm_user_session',array(
									'user_id' => $this->user()->data()->id,
									'hash' => $hash
							));
						}else{
							$hash = $hashCheck->first()->hash;
						}
						Cookies::put(Config::get('session/admin_cookie_name'), $hash, config::get('remember/expiry'));
					}
					return true;
				}
			}
		}
		return false;	
	}
	public function logout(){
		$this->_db->delete('adm_user_session', ['user_id', '=', $this->user()->data()->id]);
		Session::delete(Config::get('session/admin_session_name'));
		Cookies::delete(Config::get('session/admin_cookie_name'));
	}
	public function hasPermission($key = null) {
		if(isset($key)){
			$group = $this->_db->get('groups', array('id', '=', $this->user()->data()->group));
			if ($group->count()); {
				$permissions = json_decode($group->first()->permissions, true);
				if (isset($permissions[$key]) && $permissions[$key] == true) {
					return true;
				}
			}
		}else{
			return hasPermission("Admin");
		}
		return false;
	}
	public function isLoggedIn(){
		return $this->_loggedIn;
	}
	public function user(){
		return $this->_user;
	}
}
?>