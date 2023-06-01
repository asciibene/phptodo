<?php

function initsec($pwd){
	if(!file_exists("imp.dat"){
    write_pwd_hash($pwd);
	}else{
		print("Error");
		break;
	}
}

function checksec($p){
  // checkchecksec($_COOKIE["uindex"],$_COOKIE["hash"])=="asciibene") if hash in cookie is same as stored hash
  if(isset($_COOKIE["adminhash"]) and $_COOKIE["adminhash"] == password_hash($p, PASSWORD_DEFAULT)){
		#logged in
  }

	elseif(!isset($_COOKIE["adminhash"] and $p == $phash=file_get_contents("imp.dar")){
		setcookie("adminhash",$phash,0)

			return null;
	}
}

function verifylogin($inusr,$inpwd){
	if ($inusr=="admin" and password_verify($inpwd,$hash=file_get_contents("imp.dat"))
	setcookie('hash', $hash,0); 
		echo '<p class="notify">You are now logged in.</p>';
		echo '<a href="index.php">back to homepage</a>';
  }else {
    printError('Invalid password.');
    return null;
  }
}

function write_pwd_hash($pwd){
	file_put_contents("imp.dat",password_hash($pwd, PASSWORD_DEFAULT));
}

?>
