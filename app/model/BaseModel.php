<?php  

namespace app\model;

use app\exceptions\RouteException;
use app\interfaces\Singletone;
use app\logs\SystemLogs;
use PDO;
use PDOException;

class BaseModel extends Singletone {
	protected static $db;
	protected static $instance;
	protected $currentSet;

    static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        try {
            self::$db = @new PDO('mysql:host=localhost;dbname=' . DB_NAME, DB_LOGIN, DB_PASS);
        } catch (PDOException $e) {
            throw new RouteException("Couldn't connect to database " . DB_NAME, 2);
        }
        return self::$instance;
    }


    /**
     * u: UPDATE ($set - ['updateRecord' =>... , 'updateFields' => ...]);
     * c: INSERT ($set - ['field' => 'value'])
     */
    public function query($table, $set, $type) {
        switch ($type) {
            case 'c': return $this->insert($table, $set); break;
            case 'r': return $this->get($table, $set); break;
            case 'u': return $this->update($table, $set); break;
        }
    }

    public function updateCookie($data) {
        session_start();
        $_SESSION['auth'] = true;
        $_SESSION['login'] = $data['login'];

        $str = $this->generateString();
        BaseModel::getInstance()->query('users', [
            'updateRecord' => [
                'login' => $data['login']
            ],
            'updatedFields' => [
                'cookie' => $str
            ]
        ], 'u');

        setcookie('login', $data['login'], time()+60*60*24*30);
        setcookie('key', $str, time()+60*60*24*30);
	}
	
	protected function getWhere() {
		$compare = $this->currentSet['compare'];
		$table = $this->currentSet['table'];
		$operands = $this->currentSet['operands'];
		$whereFields = $this->currentSet['whereFields'];

		$where = '';
		$paramsToBind = [];

        if ($whereFields['password'])
        {
            $password = $whereFields['password'];
            unset($whereFields['password']);
        }

        if ($whereFields)
        foreach ($whereFields as $key => $value) {
			$paramsToBind[$key] = $value;
            if ($operands[$key])
                $where .=' '. $operands[$key] . ' ' . $table . '.' . $key;
            else
                $where .=' AND ' . $table . '.' . $key;
            if (is_array($value))
                $where .= ' IN ('. implode(', ', $value) . ')';
            else
            {
                switch($compare[$key]) {
                    case '>=':
                        $where .= " >= " . ":{$key}";
                    break;
                    case '<=':
                        $where .= " <= " . ":{$key}";
                    break;
                    case '=':
                        if (is_string($value))
                            $where .= " = :{$key}";
                        else
                            $where .= " = :{$key}";
                    break;
                    case 'LIKE': 
						$where .= " LIKE :{$key}";
                    break;
                    case '%LIKE': 
						$where .= " LIKE :{$key}";
						$paramsToBind[$key] = "%{$value}";
                    break;
                    case '%LIKE%': 
						$where .= " LIKE :{$key}";
						$paramsToBind[$key] = "%{$value}%";
                    break;
                    default: 
                        $where .= " = :{$key}";
                    break;
                }
            }
        }
		$where = ltrim($where, ' AND ');

		return [$where, $paramsToBind];
	}

	protected function getFields() {
		$fields = $this->currentSet['fields'];
		$table = $this->currentSet['table'];
		$join = $this->currentSet['join'];

		$selectFields = '';

		if ($fields) {
			foreach ($fields as $key=>$value) {
				$selectFields .= $table . '.' . $value . ', ';
			}
		} else
			$selectFields .= $table . '.*, ';

		if ($join)
		foreach($join as $joinkey=>$joinvalue) {
			if ($joinvalue['fields']) {
				foreach($joinvalue['fields'] as $key=>$value)
					$selectFields .= $joinvalue['table'] . '.' . $value . ', ';
			} else
				if ($joinvalue) $selectFields .= $joinvalue['table'] . '.*, ';
		}

		$selectFields = rtrim($selectFields, ', ');

		return $selectFields;
	}

	protected function getJoin() {
		$join = $this->currentSet['join'];

		$sqlJoin = '';

		if ($join)
		foreach($join as $key=>$value) {
			if ($value) {
				$sqlJoin .= " INNER JOIN {$value['table']}";
				$sqlJoin .= " ON {$value['condition']}";
			}
		}
		return $sqlJoin;
	}

    protected function insert($table, $set) {
        $fieldsStr = implode(", ", array_keys($set));
        if (array_key_exists('password', $set))
            $set['password'] = password_hash($set['password'], PASSWORD_BCRYPT);
        $valuesStr = "'" . implode("', '", array_values($set)) . "'";
        $sql = "INSERT INTO {$table} ($fieldsStr) 
                VALUES ($valuesStr)";
        
        $statement = self::$db->prepare($sql);
		$statement = $statement->execute();
		
		SystemLogs::writeSystemLog('SQL query: "'. $sql . '", Result: ' . boolval($statement));

        return $statement;
    }

    protected function get($table, $set) {
		$this->currentSet = $set;
		$this->currentSet['table'] = $table;

		$password = 0;
		

		$where = $this->getWhere();
		$paramsToBind = $where[1];
		$where = $where[0];
		$fields = $this->getFields();
		$join = $this->getJoin()=='' ? '' : $this->getJoin();

		$sql = "SELECT {$fields} FROM {$table} {$join} {$where}";
		$res = self::$db->prepare("SELECT {$fields} FROM {$table}" . $join . ($where!="" ? " WHERE ({$where})" : ''));

		$res->execute($paramsToBind);

		$result = $res->fetchAll(PDO::FETCH_ASSOC);

        if ($password)
            if (!password_verify($password, $result[0]['password']))
				$result = false;
				
		SystemLogs::writeSystemLog('SQL query: "'. $sql . '", Result: ' . boolval($result));

        return $result;
    }

    protected function update($table, $set) {
        $where = '';
        $setStr = '';
        foreach ($set['updateRecord'] as $key => $val) {
            $where .= "{$key}='{$val}' AND ";
        }
        $where = rtrim($where, ' AND ');
        foreach ($set['updatedFields'] as $key => $val) {
            $setStr .= "{$key}='{$val}' AND ";
        }
        $setStr = rtrim($setStr, ' AND ');
        $sql = "UPDATE {$table} SET {$setStr} WHERE {$where}";

		$res = self::$db->query($sql);
		
		SystemLogs::writeSystemLog('SQL query: "'. $sql . '", Result: ' . boolval($res));

        return $res;
    }

    protected function generateString()
    {
        $salt = '';
        $saltLength = 8;
        for($i=0; $i<$saltLength; $i++) {
            $salt .= chr(mt_rand(33,126));
        }
        return $salt;
    }

    protected function get_currency($currency_code, $format) {
    
        $date = date('d/m/Y'); // Текущая дата
        $cache_time_out = '3600'; // Время жизни кэша в секундах
    
        $file_currency_cache = __DIR__.'/XML_daily.asp';
    
        if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {
    
            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_HEADER, 0);
    
            $out = curl_exec($ch);
    
            curl_close($ch);
    
            file_put_contents($file_currency_cache, $out);
    
        }
    
        $content_currency = simplexml_load_file($file_currency_cache);
    
        return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);
    
    }

    public function gbpTOusd($item) {
        $usd = $this->get_currency('USD', 2);
        $gbp = $this->get_currency('GBP', 2);
        $usdtogbp = round($usd/$gbp, 2);
        
        $item['cost_gbp'] = floatval($item['cost_gbp']);
        $item['cost_usd'] = round($item['cost_gbp']/$usdtogbp, 2);
        
        $item['sale_gbp'] = floatval($item['sale_gbp']);
        $item['sale_usd'] = round($item['sale_gbp']/$usdtogbp, 2);

        return $item;
    }
}