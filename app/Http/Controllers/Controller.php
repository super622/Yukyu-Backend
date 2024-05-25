<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $loginUser;
    public $working_type = [
        '',
        '正社員',
        'パート(週5日)',
        'パート(週4日)',
        'パート(週3日)',
        'パート(週2日)',
        'パート(週1日)',
        'パート(不定期)'
    ];

    public function __construct() {
        session_start();
        $currentPath= Route::getFacadeRoot()->current()->uri();
        if($currentPath != "api/login" && $currentPath != "api/logout" && $currentPath != "api/regist") {
            $token = request()->header('Authorization');
            $token = explode('"}', $token)[0];
            if(!$this->isLogin($token)) {
                abort(response(['status' => 'error', 'msg' => 'ログインしてください']));
                exit;
            }
        }
    }

    /**
	 * (non-PHPdoc)
	 * @see ControllerBase::isLogin()
	 */
	protected function isLogin($token) {
		if (!isset($_SESSION['YUKYU_USER'])) {
			return false;
		} else if($_SESSION['YUKYU_USER']->token != $token) {
            return false;
        } else {
			$lastAccess = (new DateTime($_SESSION['USER_LAST_ACCESS']))->modify('+5 hour');
			$now = new DateTime();
			if ($now > $lastAccess) {
				unset($_SESSION['YUKYU_USER']);
				unset($_SESSION['USER_LAST_ACCESS']);
				return false;
			} else {
				$_SESSION['USER_LAST_ACCESS'] = $now->format('Y-m-d H:i:s');
			}
		}
		return true;
	}

    /**
	 * ログインセッションからMemberを取得する
	 */
	protected function getMemberSession() {
		if (isset($_SESSION['YUKYU_USER'])) {
			return $_SESSION['YUKYU_USER'];
		}
		return false;
	}

    /**
	 * ログインセッションにMemberを保存する
	 * @param Employee $employee
	 */
	protected function setMemberSession($employee) {
		$this->loginUser = $employee;
		$_SESSION['YUKYU_USER'] = $employee;
		$_SESSION['USER_LAST_ACCESS'] = Date('Y-m-d H:i:s');
	}

    /**
	 * ログインセッションを破棄する
	 */
	protected function deleteMemberSession() {
		unset($_SESSION['YUKYU_USER']);
		unset($_SESSION['USER_LAST_ACCESS']);
	}
}
