<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Services\DedVerify;
use App\Services\HrVerify;
use App\Http\Requests\LoginPostRequest;

use Validator, Auth, App\User, Input;

use App\Http\Requests\UpdatePostRequest;

class UserController extends Controller {

	public function __construct()
	{
		$this->middleware('auth', ['only' => ['getUpdate', 'postUpdate', 'getLogout', 'getRecommendations']]);
	}

	public function getLogin()
	{
		return view('user.login');
	}

	public function postLogin(HrVerify $hr, DedVerify $ded, LoginPostRequest $request)
	{
		$username = $request->username;
		$password = $request->password;

		if (Auth::attempt(['username' => $username, 'password' => $password], true))
		{
			return redirect('/')->withMessage(['type' => 'success', 'content' => trans('message.login.success')]);
		}
		else
		{
			if ($ded->verify($username, $password) || $hr->verify($username, $password))
			{
				$user = User::firstOrNew(['username' => $username]);
				$user->password = bcrypt($password);
				$user->avatar = intval($username) % config('business.avatar.max');
				$user->save();

				Auth::login($user, true);

				return redirect('user/update')->withMessage(['type' => 'info', 'content' => trans('message.user.info_need')]);
			}
			else
			{
				return redirect('user/login')->withMessage(['type' => 'error', 'content' => trans('message.login.failed')]);
			}
		}

		return redirect('/')->withMessage(['type' => 'success', 'content' => trans('message.login.success')]);
	}

	public function getLogout()
	{
		Auth::logout();

		return redirect('/')->withMessage(['type' => 'success', 'content' => trans('message.logout.success')]);
	}

	public function getUpdate()
	{
		return view('user.update')->withUser(Auth::user());
	}

	public function postUpdate(UpdatePostRequest $request)
	{
		$user = Auth::user();

		$user->name = $request->name;
		$user->college = $request->college;
		$user->avatar = $request->avatar;

		$user->save();

		return redirect('/')->withMessage(['type' => 'success', 'content' => trans('message.update.success')]);
	}

	public function getRecommendations()
	{
		return view('user/recommendations')
			->withRecommendations(Auth::user()->myRecommendations)
			->withVotes(Auth::user()->Votes)
			->withRemain(Auth::user()->remain());
	}

	public function getSwitch($username)
	{
		if ( !Auth::check() || !Auth::user()->isAdmin())
		{
			abort(404);
		}

		Auth::login(User::where('username', $username)->first());

		return redirect('apply/apply');
	}
}
