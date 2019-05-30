<?php

class ParentGroup {

    public $db;
    public function __construct(){
        global $DB;
        $this->db = $DB;
    }

    public function create($name, $description ){

            $this->db->insert('crm_parent_group',[
                'name'=>"'".$name."'",
                'description'=>"'".$description."'"
            ],'',false,'',false);

        return ['data'=>'success'];
    }


    public function getAllParents(){

        $q = $this->db->query('SELECT * FROM crm_parent_group ');
        $res = [];
        while($r = $q->Fetch()){
            $res[] = $r;
        }
        return $res;

    }

    public function add($id, $add_id){

        $q = $this->db->query('SELECT * FROM crm_parent_group WHERE id = "'.$id.'" ');
        $res = $q->Fetch();

        if($res['child_ids'] != ''){
            $ids = explode(',',$res['child_ids']);
        }else {
            $ids = [];
        }

        if(count($ids) > 0 ){
            $new_ids = $ids;
            foreach($ids as $i){
                if($add_id != $i){
                    $new_ids[] = $add_id;
                }
            }
            $new_ids = implode(',',$new_ids);
        }else{
            $new_ids = $add_id;
        }

        $this->db->update('crm_parent_group',[
            'child_ids'=>"'".$new_ids."'"],
            "WHERE id = '".$id."'"
        );

    }

    public function delete($id){
        $res = $this->db->query('DELETE FROM crm_parent_group WHERE id = "'.$id.'" ');
        $res->Fetch();
    }

    public function deleteChild($id){

        $q = $this->db->query('SELECT * FROM crm_parent_group');

        while($res = $q->Fetch()){

            if($res['child_ids'] != ''){
                $ids = explode(',',$res['child_ids']);
            }else {
                $ids = [];
            }

            if(count($ids) > 0 ){
                foreach($ids as $k=>$i){
                    if($id == $i){
                        unset($ids[$k]);
                    }
                }
                if(count($ids) > 0){
                    $ids = implode(',',$ids);
                }else {
                    $ids = '';
                }
                $this->db->update('crm_parent_group',[
                    'child_ids'=>"'".$ids."'"],
                    "WHERE id = '".$res['id']."'"
                );
            }
        }
    }

}