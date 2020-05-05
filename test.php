<?php



$link = mysqli_connect('localhost',"root","",'youtube','3308');
$skill = '';
if(isset($_POST['C'])){
    $skill .= $_POST['C'];
}
if(isset($_POST['Java'])){
    $skill .= $_POST['Java'];}
if(isset($_POST['Python'])){
    $skill .= $_POST['Python'];}


$name= $_POST['Name'];
$major = $_POST['Major'];
$gender = $_POST['Gender'];
$research = $_POST['research_interests'];
$email = $_POST['email'];
$Insert ="INSERT INTO skills( name,email, major,gender,skill,research) VALUES ('".$name."','"
.$email."','".$major."','".$gender."','".$skill."','".$research."')";
echo $Insert;

mysqli_query($link, $Insert) or die("Query error: ". mysqli_error($link)."\n");
$query = "SELECT * FROM skills";
$result = mysqli_query($link, $query) or die("Query error: ". mysqli_error($link)."\n");
echo "<table>\n";
echo "\t<tr>\n";
echo "\t\t<td>Name</td>\n";
echo "\t\t<td>Email</td>\n";
echo "\t\t<td>Major</td>\n";
echo "\t\t<td>Gender</td>\n";
echo "\t\t<td>Programming Skills</td>\n";
echo "\t\t<td>research interests</td>\n";
echo "\t</tr>\n";
while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    echo "\t<tr>\n";
    foreach($line as $col_value){
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";
mysqli_free_result($result);
mysqli_close($link);
?>