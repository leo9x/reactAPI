<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
	use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public static function getQrCode($code)
	{
		return 'http://www.qr-code-generator.com/phpqrcode/getCode.php?cht=qr&chl='.$code.'&chs=180x180&choe=UTF-8&chld=L|0';
	}

	public static function getCurrentUser($request)
	{
		$userToken = $request->header('PHP_AUTH_PW', '');
		$user = User::where('user_token', $userToken)->first();

		return $user;
	}

	public static function getUserByCode($id_or_code){
		$user = Self::where('qr_code',$id_or_code)->first();
		if ($user == null)
			$user = Self::where('id',$id_or_code)->first();
		return $user->toArray();
	}

	public function getUserInfo()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'phone' => $this->phone,
			'user_token' => $this->user_token,
			'point' => $this->point,
			'code' => $this->qr_code,
			'qr_code' => User::getQrCode($this->qr_code),
			'avatar' => $this->avatar,
		];
	}
}
