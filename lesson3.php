<?php
/*1. Божественный объект class C_Admin
Нужно разделить по функциональности
Тяжело наследоваться и расширять
Из-за неправильного наследования много кода и вызовов статических методов

*/

class C_Admin extends C_Base
{
	public function action_editGoodList(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Управление товарами';
		$info = "Управление товарами";

		$goods = M_Catalog::getAllStatusGoods(0,9999);
		$this->content = $this->twig()->render('v_admin_editGoodList.twig', ['text' => $info, 'goods' => $goods]);

	}

	public function action_editGood(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Редактор товара';
		$info = "Редактор товара";

		$goodId = (int)$_GET['id'];
		$good = M_Product::getGood($goodId);

		if($this->isPost()) {
			if ($_FILES['img']['error']) {
				$_FILES['img']['name'] = strip_tags($_POST['oldImg']);
			} elseif ($_FILES['img']['size'] > 1048576 ) {
				$info = "Файл слишком большого размера!";
			} elseif (exif_imagetype($_FILES['img']['tmp_name'])) {
				$path = DIR_BIG_IMG . $_FILES['img']['name'];
				if(move_uploaded_file($_FILES['img']['tmp_name'],$path)){
					$src = imagecreatefromjpeg($path);
					$imgResized = imagescale($src , 384, 256);
					imagejpeg($imgResized, DIR_SMALL_IMG . $_FILES['img']['name']);
				}
			}
			$admin = new M_Admin();
			$res = $admin->editGood(strip_tags($_POST['name']), strip_tags($_POST['description']), strip_tags($_POST['price']), $_FILES['img']['name'], $goodId);
			$res ? header("Location: index.php?c=Product&act=open&id=$goodId") : $info = "Ошибка редактирования";
		}

		$this->content = $this->twig()->render('v_admin_editGood.twig', ['text' => $info, 'good' => $good]);
	}

	public function action_addGood(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Добавление товаров';
		$info = "Добавление нового товара";

		if($this->isPost()) {
			if ($_FILES['img']['error']) {
				$info = "Ошибка файла";
			} elseif ($_FILES['img']['size'] > 1048576 ) {
				$info = "Файл слишком большого размера!";
			} elseif (exif_imagetype($_FILES['img']['tmp_name'])) {
				$path = DIR_BIG_IMG . $_FILES['img']['name'];
				if(move_uploaded_file($_FILES['img']['tmp_name'],$path)){
					$src = imagecreatefromjpeg($path);
					$imgResized = imagescale($src , 384, 256);
					imagejpeg($imgResized, DIR_SMALL_IMG . $_FILES['img']['name']);
				}
			}
			$admin = new M_Admin();
			$res = $admin->addGood(strip_tags($_POST['name']), strip_tags($_POST['description']), strip_tags($_POST['price']), $_FILES['img']['name'], $_POST['active']);
			$res ? header("Location: index.php?c=Product&act=open&id=$res") : $info = "Ошибка добавления";
		}
		$this->content = $this->twig()->render('v_admin_addGood.twig', ['text' => $info]);	
	}

	public function action_delGood(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Удаление товара';
		$info = "Удаление товара";
		$admin = new M_Admin();
		$res = $admin->delGood((int)$_GET['id']);
		$res ? header("Location: index.php?c=Admin&act=editGoodList") : $info = "Ошибка Удаления";	
	}

	public function action_editOrder(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Управление заказами';
		$info = "Управление заказами";

		$orders = M_Order::getAllOrders();
		$this->content = $this->twig()->render('v_admin_editOrder.twig', ['text' => $info, 'orders' => $orders]);
	}

	public function action_editRating(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Управление отзывами';
		$info = "Управление отзывами";

		$ratings = M_Rating::getAllRatings();
		$this->content = $this->twig()->render('v_admin_editRating.twig', ['text' => $info, 'ratings' => $ratings]);
	}

	public function action_editUser(){
		if (!$_SESSION['admin']) {
			header("Location: index.php?c=User&act=lk");
		}
		$this->title .= '::Управление пользователями';
		$info = "Управление пользователями";

		$sql = "SELECT * FROM users";
        $users = MPDO::Select($sql);
		$this->content = $this->twig()->render('v_admin_editUser.twig', ['text' => $info, 'users' => $users]);
	}
}

/* 2. Спагетти-код
    Много лишних проверок, от этого много лишних return'ов
    Нужно упростить
*/
public static function addToCart($goodId, $userId, $count = 1) {
    if (!$userId) {
        $week = time() + (7 * 24 * 60 * 60);
        if(!empty($_COOKIE["cart"][$goodId])){
            if(setcookie("cart[$goodId]",++$_COOKIE["cart"][$goodId],$week,'/')){
                return 1;
            }
            return false;
        };
        if(setcookie("cart[$goodId]",1,$week,'/')){
            return 1;
        }
        
    } else {
        $sql="select * from cart inner join `users` on `cart`.`user_id`=`users`.`id` WHERE `good_id`='$goodId' AND `user_id`='$userId'";
        $existGoods = MPDO::getRow($sql);

        if (!$existGoods) {
            $sql="INSERT INTO `cart` (`user_id`, `good_id`, `count`) VALUES ('$userId', '$goodId', '$count');";
            $res = MPDO::insert($sql);
        } else {
            $sql = "UPDATE `cart` SET `count` = `count` + $count WHERE `good_id`='$goodId' AND `user_id`='$userId'";
            $res = MPDO::update($sql);	
        }
    }

}

/*3. Божественный объект class C_Ajax
Нужно разделить по функциональности
Наблюдается дублирование кода и вызовов статических методов, когда можно было сделать просто через соответсвующий объект

*/
class C_Ajax
{
	public function Request($action)
	{
		$this->$action();
	}

	public function action_moreGoods(){
		if ((int)$_POST['lastView']){
			$goods = M_Catalog::getGoods((int)$_POST['lastView']);
			$goodsList = C_Base::twig()->render('v_catalog_goods.twig', ['goods' => $goods, 'id_user' => $_SESSION['id_user']]);
			echo $goodsList;
		}
	}

	public function action_addToCart(){
		if ((int)$_POST['goodId']){
			return M_Product::addToCart((int)$_POST['goodId'], (int)$_POST['userId']);
		}   else return false; 
	}

	public function action_delFromCart(){
		if ((int)$_POST['goodId']){
			return M_Cart::delFromCart((int)$_POST['goodId'], (int)$_POST['userId']);
		}   else return false; 
	}

	public function action_createOrder(){
		if ((int)$_POST['userId']){
			return M_Cart::createOrder((int)$_POST['userId']);
		}
	}

	public function action_cleanCart(){
			return M_Cart::cleanCart((int)$_POST['userId']);
	}

	public function action_galary(){
			$files = M_Product::getGoodPhotos((int)$_POST['goodId']);
			$photos = C_Base::twig()->render('v_photos.twig', ['files' => $files, 'good_id' => (int)$_POST['id']]);
			echo $photos;
	}

	public function action_refreshCart(){
			$userCart = M_Cart::getCart((int)$_POST['userId']);
			$goodsList = C_Base::twig()->render('v_cart_goods.twig', ['cart' => $userCart, 'id_user' => $_SESSION['id_user']]);
			echo $goodsList;		
	}

	public function action_changeStatusGood(){
		if ($_SESSION['admin']) {
			$status = strip_tags($_POST['newStatus']);
			$id = (int)($_POST['goodId']);
			$sql = "UPDATE `goods` SET `active` = '$status' WHERE `id` = $id";
			return $res = MPDO::update($sql);
			} else return false;		
	}

	public function action_delOrder(){
		if ($_SESSION['admin']) {
			$orderId = (int)$_POST['orderId'];
			$sql = "DELETE FROM `orders` WHERE `id` = '$orderId'";
        	return $res = MPDO::delete($sql);
		} else return false;
	}

	public function action_changeStatusOrder(){
		if ($_SESSION['admin']) {
		$status = strip_tags($_POST['newStatus']);
		$id = (int)($_POST['orderId']);
		$sql = "UPDATE `orders` SET `status` = '$status' WHERE `id` = '$id'";
		return $res = MPDO::update($sql);
		} else return false;		
	}

	public function action_delRating(){
		if ($_SESSION['admin']) {
			$ratingId = (int)$_POST['ratingId'];
			$sql = "DELETE FROM `rating` WHERE `id` = '$ratingId'";
        	return $res = MPDO::delete($sql);
		} else return false;
	}

	public function action_changeStatusRating(){
		if ($_SESSION['admin']) {
		$status = strip_tags($_POST['newStatus']);
		$id = (int)($_POST['ratingId']);
		$sql = "UPDATE `rating` SET `active` = '$status' WHERE `id` = $id";
		return $res = MPDO::update($sql);
		} else return false;		
	}

	public function action_delUser(){
		if ($_SESSION['admin']) {
			$userId = (int)$_POST['userId'];
			$sql = "DELETE FROM `users` WHERE `id` = '$userId'";
        	return $res = MPDO::delete($sql);
		} else return false;
	}

	public function action_changeGroupUser(){
		if ($_SESSION['admin']) {
		$group = strip_tags($_POST['newGroup']);
		$id = (int)($_POST['userId']);
		$sql = "UPDATE `users` SET `usergroup` = '$group' WHERE `id` = $id";
		return $res = MPDO::update($sql);
		} else return false;		
	}

	public function action_changeStatusUser(){
		if ($_SESSION['admin']) {
		$status = strip_tags($_POST['newStatus']);
		$id = (int)($_POST['userId']);
		$sql = "UPDATE `users` SET `active` = '$status' WHERE `id` = $id";
		return $res = MPDO::update($sql);
		} else return false;		
	}


}

/*4. singleton 
Поискать решение на Dependency injection
Пока применение оправдано легким, понятным и безопасным доступом к бд
 */

class MPDO {

    protected static $instance = null;

    private function __construct() {
        
    }

    private function __clone() {                 
        
    }

    /**
     * 
     * @return \PDO
     */
    private static function instance() {
        if (self::$instance === null) {
            $opt = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => TRUE,
            );
            $dsn = DB_DRIVER . ':host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=' . DB_CHAR;
            self::$instance = new \PDO($dsn, DB_USER, DB_PASSWORD, $opt);
        }
        return self::$instance;
    }
                                                                                                                                                 
    /**
     * 
     * @param string $sql
     * @param array $args
     * @return \PDOStatement
     */
    private static function sql($sql, $args = []) {
        //echo "<pre>".$sql."</pre>";
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    /**
     * 
     * @param string $sql
     * @param array $args
     * @return array
     */
    public static function Select($sql, $args = []) {
        return self::sql($sql, $args)->fetchAll();
    }

    /**
     * 
     * @param string $sql
     * @param array $args
     * @return array
     */
    public static function getRow($sql, $args = []) {
        return self::sql($sql, $args)->fetch();
    }

    /**
     * 
     * @param string $sql
     * @param array $args
     * @return integer ID
     */
    public static function insert($sql, $args = []) {
        self::sql($sql, $args);
        return self::instance()->lastInsertId();
    }

    /**
     * 
     * @param string $sql
     * @param array $args
     * @return integer affected rows
     */
    public static function update($sql, $args = []) {
        $stmt = self::sql($sql, $args);
        return $stmt->rowCount();
    }

    /** 
     * 
     * @param string $sql
     * @param array $args
     * @return integer affected rows
     */
    public static function delete($sql, $args = []) {
        $stmt = self::sql($sql, $args);
        return $stmt->rowCount();
    }

}