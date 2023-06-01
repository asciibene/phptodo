<html>
<head>
<title>phpTodo</title>
<link rel="stylesheet" type="text/css" href="style.css" /> 
</head>
<body>
<?php
// Here the ifs for stuff not requiring forms....
// ie the action triggered BY the forms...
//(Now is all included inside the include 
  include_once "func.php";
    
# page starts below
?>


<h1 id="logo">phpTodo <span class="ver_num"><?= VER ?></span></h1>
<?php indoc_display_items(); 
if(!empty($_GET)): ?>
  <form method="post" action="index.php">
	<button name="act" value="select_reset">Unselect task </button>
	</form>

<? else: ?>
  
<? endif; ?>

<hr>

<?php
//below the ifs for actions that show a form  
if(($_POST['act']=="show_upd_form" or $_GET['act']=="show_upd_form") and isset($_GET['id'])): ?>
<h2>edit todo</h2>
<?php echo '<form action="index.php?id='.$_GET['id'].'" method="post">'; ?>

<label for="new_priority">Priority</label>
<select name="new_priority">
<option>High</option>
<option>Normal</option>
<option>Low</option>
</select><br>

<label for="new_state">State</label>
<select name="new_state">
<option>No state</option>
<option>Started</option>
<option>Finished</option>
</select><br>

<label for="new_desc">Description</label><br>
<textarea name="new_desc">
<?= $db[$_GET['id']]->desc ?>
</textarea><br>
<button name="act" value="upd_task">submit</button>
</form>
<hr>
<?php echo '<form action="index.php?id='.$_GET['id'].'" method="post"><br>'; ?>
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