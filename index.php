<html>
<head>
<title>phpTodo</title>
<link rel="stylesheet" type="text/css" href="./style.css"/> 
</head>
<body>
<?php
include_once "func.php";
include_once "class.php";    
?>

<h1 id="logo">phpTodo <span class="ver_num"><?= VER ?></span></h1>
<?php
    indoc_display_items(); 
	if(!empty($_GET)): ?>
      <form method="post" action="index.php">
	  <button name="act" value="select_reset">Unselect task </button>
	</form>  
<?php endif; ?>
<hr>
<?php
//below the ifs for actions that show a form  
if(($_POST['act']=="show_upd_form" or $_GET['act']=="show_upd_form") and isset($_GET['id'])): ?>

<hr>
<?= '<form action="index.php?id='.$_GET['id'].'" method="post"><br>'; ?>
<label for="new_sub">New Subtask</label><br>
<input name="new_sub_title" type="text"></input><br>
<button type="submit" name="act" value="new_sub">Submit</button>
</form>

<?php else: ?>

<h2>New task</h2>
<form action="index.php?act=new_task" method="post" >
 <input name="new_title" type="text" />
 <button>Submit</button>
</form>
<?php endif; ?>
</body>
</html>
