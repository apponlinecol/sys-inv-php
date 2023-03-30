<?php require_once 'connections.php';

class ModelGeneral
{
    static public function mdlRecord( $type, $table, $other )
    {
        $stmt = connections::connect()->prepare(' SELECT * FROM '.$table.' '.$other );
        $stmt -> execute();

        switch ($type) {
            case 'all': return $stmt -> fetchAll(); break;
            case 'single': return $stmt -> fetch(); break;
        }

        $stmt = null;
    }
    static public function mdlInsertRow( $table, $header, $data )
    {
        try {
            $fiel = []; $valu = []; foreach ($header as $row) { $fiel[] = '`' . $row . '`'; $valu[] = ':' . $row; }
            $fields = implode(',', $fiel); $values = implode(',', $valu);
            $stmt = connections::connect()->prepare(' INSERT INTO ' . $table . ' ( ' . $fields . ' ) VALUES ( ' . $values . ' ) ');
            foreach ($data as $key => $value) {
                if (is_int($value)) { $param = PDO::PARAM_INT; }
                else { $param = PDO::PARAM_STR; }
                $stmt->bindValue(':' . $key, $value, $param);
            }
            $stmt->execute();
            return 'ok';
        } catch ( Exception $e ){
            switch ( $e->getCode() ){ case 23000: return 'repeated'; break; default: return $e->getCode(); break; }
        }
    }
    static public function mdlUpdateRow( $table, $title, $data )
    {
        try {
            $set = []; foreach ($title as $row) { $set[] = '`' . $row . '`'. '= :' .$row; }
            $set = implode(',', $set);
            $stmt = connections::connect()->prepare(' update '.$table.' set '.$set.' where id = :id ');
            foreach ($data as $key => $value) {
                if (is_int($value)) { $param = PDO::PARAM_INT; } else { $param = PDO::PARAM_STR; }
                $stmt->bindValue(':' . $key, $value, $param);
            }
            $stmt->execute();
            return 'ok';
        } catch ( Exception $e ){
            switch ( $e->getCode() ){ case 23000: return 'repeated'; break; default: return $e; break; }
        }
    }
    static public function mdlUpdateField( $table, $set, $data )
    {
        try {
            $stmt = connections::connect()->prepare(' update '.$table.' set '.$set.' = :set where id = :id ' );
            foreach ( $data as $key => $value ){
                if( is_int($value) ){ $param = PDO::PARAM_INT; } else { $param = PDO::PARAM_STR; }
                $stmt->bindValue(':'.$key, $value, $param );
            }
            $stmt->execute();
            return 'ok';
        } catch ( Exception $e ){
            return $e->getCode();
        }
    }

    static public function mdlExecuteQuery( $query )
    {
        $data = connections::connect()->query( $query );
        return $data->fetchAll();
    }
    static public function mdlDataTableDynamic( $header, $query )
    {
        $head = connections::connect()->query( $header );
        $execution = connections::connect()->query( $query );
        $data = $execution->fetchAll();
        $info = [ "header" => array_keys( $head->fetch(PDO::FETCH_ASSOC)), "data" => $data, ];
        return $info;
    }

    static public function mdlRemoveRow( $table, $idr ){
        try {
            $stmt = connections::connect()->prepare(' delete from '.$table.' where id='.$idr );
            $stmt->execute();
            return 'ok';
        } catch ( Exception $e ){
            switch ( $e->getCode() ){ default: return $e->getCode(); break; }
        }
    }
}