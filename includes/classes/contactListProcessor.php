<?php


class contactListProcessor
{
    private $con,$usernameLoggedIn,$sqlData,$table,$view,$viewquery,$viewfilter,$filtertable;
    public function __construct($con,$usernameLoggedIn)
    {
        $this->con = $con;
        $this->usernameLoggedIn = $usernameLoggedIn;
    }
    public function query(){
        $query = $this->con->prepare("SELECT * From contactlist WHERE mainuser =:mainUser");
        $query->bindParam(':mainUser',$this->usernameLoggedIn);
        $query->execute();
        $this->sqlData = $query->fetchAll(PDO::FETCH_ASSOC);
        return $this->sqlData;
    }
    private function checkDuplicate($username){
        $query = $this->con->prepare("SELECT * From contactlist WHERE mainuser =:mainUser and username=:username");
        $query->bindParam(':mainUser',$this->usernameLoggedIn);
        $query->bindParam(':username',$username);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);

    }
    private function userexist($username){
        $query = $this->con->prepare("SELECT * From users WHERE username=:username");
        $query->bindParam(':username',$username);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);

    }

    public function fetchData()
    {
        $this->query();
        $count = 1;
        foreach ($this->sqlData as $key => $value) {
            $block = '';
            if($value['blocked'] == 1)
            {
                $block = 'V';
            }

            $this->table .= '<tr> <th scope="row">'.$count.'</th>';
            $this->table .= '<td>' . '<input type="checkbox" name="contactList[]" value = "'.$value['username'] . '"> </td>' ;
            $this->table .= '<td>' . $value['username'] . '</td>' . '<td>' . $value['groupname'] . '</td>'. '<td>' . $block . '</td>';
            $this->table .= '</tr>';
            $count++;
        }
        return "$this->table";
    }

    public function deleteContact($deleteList){
        $qMarks = str_repeat('?,', count($deleteList) - 1) . '?';
        $mainUser = "'".$this->usernameLoggedIn."'";
        $query = $this->con->prepare("DELETE FROM contactlist WHERE mainuser= $mainUser AND username IN ($qMarks)");
        $query->execute($deleteList);
    }
    public function addContact($contactName,$groupName,$block){
        if(!strcmp($contactName,$this->usernameLoggedIn))
        {
            return "You cannot add yourself";
        }
        if(!empty($this->checkDuplicate($contactName))){
            return "You already have this person ".$contactName." in your contact list";
        }
        if(empty($this->userexist($contactName))){
            return "The username ".$contactName." that you input not exist in MeTube system";
        }

        $query = $this->con->prepare("INSERT INTO contactlist (mainuser,username,groupname,blocked) value(:mainuser,:contactName,:groupName,:blocked)");
        $query->bindParam(':mainuser',$this->usernameLoggedIn);
        $query->bindParam(':contactName',$contactName);
        $query->bindParam(':groupName',$groupName);
        $query->bindParam(':blocked',$block);
        $query->execute();
    }
    public function blockContact($blockList,$block){

        $qMarks = str_repeat('?,', count($blockList) - 1) . '?';
        $mainUser = "'".$this->usernameLoggedIn."'";
        $query = $this->con->prepare("UPDATE contactlist set blocked= $block WHERE mainuser= $mainUser AND username IN ($qMarks)");
        $query->execute($blockList);
    }
    public function getviewfilter(){
        $query = $this->con->prepare("SELECT groupname From contactlist WHERE mainuser =:mainUser");
        $query->bindParam(':mainUser',$this->usernameLoggedIn);
        $query->execute();
        $this->viewquery = $query->fetchAll(PDO::FETCH_ASSOC);
        print_r($this->viewquery);
        foreach ($this->viewquery as $key => $value) {
            $this->view .= '<option value="' .$value[groupname].'">'.$value[groupname].'</option>';
        }
        return "$this->view";
    }
    public function viewFilter($groupname){

        $query = $this->con->prepare("SELECT * From contactlist WHERE mainuser =:mainUser and groupname=:groupname");
        $query->bindParam(':mainUser',$this->usernameLoggedIn);
        $query->bindParam(':groupname',$groupname);
        $query->execute();
        $this->viewfilter = $query->fetchAll(PDO::FETCH_ASSOC);
        $count = 1;
        foreach ($this->viewfilter as $key => $value) {
            $block = '';
            if($value['blocked'] == 1)
            {
                $block = 'V';
            }

            $this->filtertable .= '<tr> <th scope="row">'.$count.'</th>>';
            $this->filtertable .= '<td>' . '<input type="checkbox" name="contactList[]" value = "'.$value['username'] . '"> </td>' ;
            $this->filtertable .= '<td>' . $value['username'] . '</td>' . '<td>' . $value['groupname'] . '</td>'. '<td>' . $block . '</td>';
            $this->filtertable .= '</tr>';
            $count++;
        }
        return "$this->filtertable";
    }

}