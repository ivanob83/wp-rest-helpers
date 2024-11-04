<?php 
global $wpdb;
$wpdb->show_errors = false;

class Database {
    public static function create($class) {
        global $wpdb;
        $dbName = $class::$name;
        $table_name = $wpdb->prefix . $dbName;

        $fields = $class::$fields;

        $charset_collate = $wpdb->get_charset_collate();

        $sqlFields = array();

        if(!empty($fields)) {
            foreach($fields AS $key=>$val) {
                $sqlFields[] = $val['name'].' '.$val['type'];
                if(isset($val['primary'])) {
                    $sqlFields[] = 'PRIMARY KEY ('.$val['name'].')';
                }
            }
        }
        $sqlFields = implode(", ", $sqlFields);

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            $sqlFields
        ) $charset_collate;";

        $wpdb->query($sql);
    }

    public static function returnData($results) {
        global $wpdb;
        if($wpdb->last_error !== '') :
            $return = new stdClass;
            $return->error = $wpdb->last_error;
            return $return;
        else :
            return $results;
        endif;
    }

    public static function get($table_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        
        $results = $wpdb->get_results("SELECT * FROM $table_name");
        return self::returnData($results);
    }

    public static function getItem($request, $table_name, $primaryKey) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        $where = "WHERE ".$primaryKey ." = ". $request['id'];
        $results = $wpdb->get_results("SELECT * FROM $table_name $where");
        return self::returnData($results);
    }

    public static function insert($request, $table_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        
        $results = $wpdb->insert(
            $table_name,
            $request
        );
        return self::returnData($results);
    }

    public static function update($request, $table_name, $primaryKey) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        
        $results = $wpdb->update(
            $table_name,
            $request,
            array($primaryKey => $request[$primaryKey])
        );
        return self::returnData($results);
    }

    public static function delete($request, $table_name, $primaryKey) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        
        $results = $wpdb->delete(
            $table_name,
            array($primaryKey => $request[$primaryKey])
        );
        return self::returnData($results);
    }
}
?>