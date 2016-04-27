<?php
function generate($size = 8){
echo "Vase heslo: <b>";
for($n = 1, $special = rand(1, $size);$n <= $size; $n++){
	$char = [
		chr(rand(65, 90)),  // velka pismena
		chr(rand(97, 122)), // mala pismena
		chr(rand(48, 57)),  // cislice 1-9
		chr(43)   // specialni znak +
	];
	echo ($n == $special) ? $char[3]  : $char[rand(0, 2)];
}
echo "</b>";
}

if(isset($_POST["size"])){
$size = htmlspecialchars($_POST["size"], ENT_QUOTES);
generate($size);
}

?>

<form action="index.php" method="post">
Delka hesla: <input type = "text" maxlenght="3" name="size" size="3"  value="8">
<input type="submit" value="Generovat heslo!">
</form>
