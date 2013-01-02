<?php

    if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}

    // plotseme user class
    class user{
	    var $user_id;
	    var $user_name;
	    var $user_login;
	    var $user_password;
	    var $user_privilege;
	    var $user_email;
	    var $user_description;
	    var $user_ok = FALSE;

	    // class constructor, log user
        function user($login, $password){

            // check admin user log and pass
            if (($login == ADMIN_USER_NAME) && (($password == md5(ADMIN_USER_PASSWORD)))){

                $this->user_ok = TRUE;
                $this->user_privilege = 2; // full privilege
                $this->user_name = "Admin";
                $this->user_login = $login;
                $this->user_password = $password;

                log_err('admin user logged-in');

            } else { // get user in sql users table
				$query = sprintf("SELECT * FROM ".SQL_TABLE_USERS." WHERE (login='%s' AND password='%s') LIMIT 1;",
				mysql_real_escape_string(stripslashes($login)), mysql_real_escape_string(stripslashes($password)));
			
               // $query = "SELECT * FROM ".SQL_TABLE_USERS." WHERE (login='$login' AND password='$password') LIMIT 1";
                //echo  $query ;
                $result = mysql_query($query);

                if ($result) {
                    if (mysql_num_rows($result) == 0) { // no user found
                        $this->user_ok = FALSE;
                        $this->user_privilege = 0; // no privilege
                        log_err('wrong name or password');
                    } else {
                        $this->user_id = @mysql_result($result, 0, "id");
                        $this->user_name = stripslashes(@mysql_result($result, 0, "name"));  
                        $this->user_login = stripslashes(@mysql_result($result, 0, "login"));
                        $this->user_password = stripslashes(@mysql_result($result, 0, "password"));
                        $this->user_privilege = stripslashes(@mysql_result($result, 0, "privilege"));
                        $this->user_email = stripslashes(@mysql_result($result, 0, "email"));
                        $this->user_description = stripslashes(@mysql_result($result, 0, "description"));
                        $this->user_ok = TRUE;

                        log_err('user '.$this->user_name.' logged-in with privilege : '.$this->user_privilege);
                    }

                    mysql_free_result($result);

                } else {
                    log_err('User Class, MySql error');
                } 
            }
	    }


	    // user creation fonction
	    function user_create($login, $password, $name, $description, $email, $privilege=0){
			
			
            $query = "SELECT login FROM ".SQL_TABLE_USERS." WHERE (login='$login') LIMIT 1";
            $result = mysql_query($query);
                    
            if ($result) {
                if (mysql_num_rows($result) == 0) { // no user found with same login, ok
                    mysql_free_result($result);
                    $password = md5($password); // scramble it
                    $query = "INSERT INTO ".SQL_TABLE_USERS." (id,login,password,name,email,description,privilege) VALUES ('','$login','$password','$name','$email','$description','$privilege');";
                    $result = mysql_query($query);
                    return true;
                } else { // login allready exist
                    log_err('User Class->create, login allready exist');
                    return false;
                }
            } else {
                log_err('User Class->create, MySql error');
                return false;
            }
	    }

        // user delete function
	    function user_delete($users_id){

            foreach ($users_id as $id) { // delete all users in array
                $query = "DELETE FROM ".SQL_TABLE_USERS." WHERE (id='$id') LIMIT 1";
                $result = mysql_query($query);

                if ($result) {
                    log_err('User Class->delete, ok');

                } else {
                    log_err('User Class->delete, MySql error');
                    $error = true;

                }
            }

		    return !$error;
	    }

	    function get_user_list(){


	    }

	    function get_user_log_form(){


	    }

    }
?>