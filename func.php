<?php


include_once("class.php");
define("DBLOC","./db/todo.db");
define("VER","v0.17");

// Constants END

//  ----------- Helper Funcs ------------- (eventually move to another file)
function notify($nstr){
  echo $fullstr='<small class="notify">'.$nstr.'</small>';
  return $fullstr;
}

function mktag($tag,$content,$atlist = null){
  if($atlist == null):
     return "<".$tag.">".$content."</".$tag.">";
   else:
     $attr_string="";
     foreach($atlist as $atr=>$val):
       $attr_string = $attr_string.$atr.'="'.$val.'" ';
     endforeach;
   return "<".$tag.$attr_string.">".$content."</".$tag.">";
  endif;
}

function get_from_uid($inuid){
  global $db;
  foreach($db as $k=>$obj){
    if($inuid == $obj->uid)
        return $obj; 
  }
  return false;
}

// ────End of helper funcs ─────────────

function resetdb(){
  global $db;
  $db = [];
  $dbtxt=serialize($db);
  file_put_contents(DBLOC,$dbtxt);
  savedb();
}

function initdb(){
  global $db;
  if (file_exists(DBLOC)){
    $dbtxt=file_get_contents(DBLOC);
    $db=unserialize($dbtxt);
    return true;
  }else{
    print("DB File not found, making one...");
   $db = [];
   $dbtxt=serialize($db);
    file_put_contents(DBLOC,$dbtxt);
    return false; 
  } 
}

function savedb(){
  global $db;
   file_put_contents(DBLOC,serialize($db));
  return true;          
}
// --------------- End  of helper funcs -------------------------------------
// -------------- Indocument functions ────────────────────

function indoc_display_items(){
  global $db;
  db_sort_by_priority();
  echo '<ul class="todo_list">';
  foreach($db as $k=>$obj):
    if(isset($_GET['id']) and $_GET["id"]==$k):
       #IF ITEM SELECTD
       $subcount=sizeof($obj->sub)!=0 ? '('.sizeof($obj->sub).')' : "";
        print('<li><h3 class="'.'selected'.'">'.$obj.'<span class="sub_count">'.$subcount.'</span></h3><br>');
         indoc_view_details($_GET['id']);
      else: # IF NO ITEM SELECTED below
        $htmclass = $obj->state == "Finished" ? "finished" : 'started';
        $subcount = sizeof($obj->sub)!=0 ? '('.sizeof($obj->sub).')' : "";
        #subcount INCLUDES the parenthesis
        if($htmclass=="finished"):
          print('<li class="'.$class.'"><del><a href="index.php?id='.$k.'">'.$obj."</a></del>".mktag('span',$subcount,["class"=>"sub_count"]));
        else:
          $objpri = $obj->priority;
           print('<li class="'.$objpri.'"><a href="index.php?id='.$k.'&act=show_upd_form">'.$obj."</a>".mktag('span',$subcount,["class"=>"sub_count"]));
        endif;
     endif;
    endforeach;
    echo "</ul>";
    //echo '<ul class="done_list">';
    
}

function db_sort_by_priority(){
  global $db;
  $newdb=[];
  foreach($db as $k=>$obj):
    if($obj->priority=="High" and $obj->state!="Finished"):
      $newdb[]=$obj;
      unset($db[$k]);
    endif;
  endforeach;
  foreach($db as $k=>$obj):
    if($obj->priority=="Normal" and $obj->state!='Finished'):
           $newdb[]=$obj;
           unset($db[$k]);
  endif;
  endforeach;
  foreach($db as $k=>$obj):
      if($obj->priority=="Low" or $obj->state=='Finished')
           $newdb[]=$obj;
  endforeach;
  $db = $newdb;
  savedb();
}

function indoc_display_edit_forms(){
$form_string= <<<FSTR
	<h2>edit todo</h2>
	<form action="index.php?id='.#{$_GET['id']}.'" method="post">

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
	<textarea name="new_desc"> #{$db[$_GET['id']]->desc} </textarea><br>
	<button name="act" value="upd_task">submit</button>
	</form>
FSTR;
	
}



function indoc_new_todo($ttl){
  global $db;
  if($ttl==""){ 
    print "Error - Empty string or non-string submitted";
    return false;
  } 
  array_push($db,new TodoObject($ttl));
  db_sort_by_priority();
  notify('Created new todo '.$ttl);
  savedb();
}

function indoc_view_details($taskid){
// task id is the order of db array
  global $db;
   if(array_key_exists($taskid, $db)):
      $tobj=$db[$taskid];
      print mktag('em',$tobj->desc??"No Description");
     echo '<ul class="detail_list">';
       // Show details and form to upd it
     $diff=date_diff($tobj->timestamp,date_create('now'));
      print mktag('li',mktag('date','created '.$diff->format('%a days, %h hour(s), %i minute(s) ago')));
      print mktag('li',mktag('span','state: '.$tobj->state));
      print mktag('li','priority: '.$tobj->priority);
      print mktag('small','uid: '.$tobj->uid);
    
      //Buttons etc <--------
      echo '<form action="index.php?id='.$taskid.'" method="post"><button name="act" value="delete_task">Delete</button></form>';
      echo '</ul>';

      //subtask list <------
          echo('<ul class="sub_list"><form action="index.php?id='.$taskid.'&act=del_sub" method="post">');
         foreach($tobj->sub as $k=>$s):
          print('<li>'.$s['title']);
          print('<button name="del_su
        
        b_id" value="'.$k.'">x</button>'); 
          print('</li>');
        endforeach; 
        echo '</ul></form>';
      // End subtask list ───────────────────────
   else:
    print("error - id does not exist"); 
     return false;
  endif;  
      
}

function indoc_update_task($id){
// ndarr -> array of new details (use pack funcs)
  global $db;
  $db[$id]->priority=$_POST['new_priority'] ?? $db[$id]->priority; 
  $db[$id]->state=$_POST['new_state'] ?? $db[$id]->state;
  if($_POST['new_desc'] != "")
      $db[$id]->desc =  $_POST['new_desc'] ?? $db[$id]->desc;
  savedb();
}

function indoc_new_sub(){
  global $db;
  if(empty($_GET) or empty($_POST)){ return false; }
  $id=$_GET['id'];
  if(!empty($_POST['new_sub_title'])){
    $db[$id]->sub[] = ['title'=>$_POST['new_sub_title']];
  }
  savedb();
}

function indoc_delete_task($taskid){
  global $db;
  unset($db[$taskid]);
  unset($_POST['id']);
  db_sort_by_priority(); 
  savedb();
  notify('Deleted task - id #'.$taskid);
}

function indoc_delete_sub($taskid,$subid){
  global $db;
  unset($db[$taskid]->sub[$subid]);
  savedb();
}

// ──────────────── End of indoc funcs -------------


// ───────────── MAIN STUFF ────────────-------------------------------------------------------------

initdb();
//Check page action --> put into separate file eventually
if($_GET['reset']=='yes'){ resetdb(); }
if($_GET['act']=="select_reset"){ unset($_GET['id']); }
if($_GET['act']=="new_task" and isset($_POST['new_title'])):
    indoc_new_todo($_POST["new_title"]);
elseif($_POST["act"]=="upd_task" and isset($_GET['id']) and !empty($_POST)):
    indoc_update_task($_GET['id']);
elseif($_POST['act']=="new_sub" and isset($_GET['id']) and !empty($_POST["new_sub_title"])):
    indoc_new_sub();    
elseif($_POST['act']=="delete_task" and isset($_GET['id'])):
    indoc_delete_task($_GET['id']);
elseif($_GET['act']=="del_sub" and isset($_GET['id']) and isset($_POST['del_sub_id'])):
  indoc_delete_sub($_GET['id'],$_POST['del_sub_id']);
endif;

