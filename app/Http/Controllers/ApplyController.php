<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth, App\User, App\Apply, App\Http\Requests\ApplyPostRequest;

use Input, App\Recommendation, App\Http\Requests\RecommendPostRequest;
use Session;

class ApplyController extends Controller {

	public function __construct()
	{
		$this->middleware('auth', ['only' => ['getApply', 'postApply', 'postRecommendation']]);
	}

	public function getApply()
	{
		return view('apply.apply')->withApply(Auth::user()->apply)->withStuid(Auth::user()->username);
	}

	public function postApply(ApplyPostRequest $request)
	{
		$request->photos = [];
		foreach ($request->file('imgs') as $file) {
			if ($file)
			{
				if (in_array($file->getClientMimeType(), config('business.MIME')) && in_array($file->getClientOriginalExtension(), array_keys(config('business.MIME')))){
					$filename = md5($file->getClientOriginalName().$file->getClientSize()).'.'.$file->getClientOriginalExtension();
					$file->move(config('business.photo'), $filename);
					$request->photos[] = $filename;
				}
				else{
					abort(500, trans('invalid_file_type.'));
				}
			}
		}

		$user = Auth::user();

		$apply = Apply::firstOrNew(['user_id' => $user->id]);

		$apply->type = $request->type;
		$apply->stuid = $user->username;
		$apply->name = Auth::user()->name;
		$apply->college = Auth::user()->college;
		$apply->sex = $request->sex;
		$apply->native_place = $request->native_place;
		$apply->political = $request->political;
		$apply->major = $request->major;
		$apply->title = $request->title;
		$apply->whoami = $request->whoami;
		$apply->story = $request->story;
		$apply->insufficient = $request->insufficient;
		$apply->tag1 = isset($request->tags[0]) ? $request->tags[0] : '';
		$apply->tag2 = isset($request->tags[1]) ? $request->tags[1] : '';
		$apply->tag3 = isset($request->tags[2]) ? $request->tags[2] : '';

		foreach ($request->photos as $key => $photo) {
			$keyname = 'img'.($key+1);
			$apply->$keyname = $photo;
		}

		$apply->intro1 = isset($request->intros[0]) ? $request->intros[0] : '';
		$apply->intro2 = isset($request->intros[1]) ? $request->intros[1] : '';
		$apply->intro3 = isset($request->intros[2]) ? $request->intros[2] : '';

		$user->apply()->save($apply);

		return redirect('apply/apply')->withMessage(['type' => 'success', 'content' => trans('message.apply_successed')]);
	}

	public function getShow($id, Request $request)
	{
		$apply = Apply::find($id);

		if ($apply){

			$apply->increment('pageview');
			$apply->isRecommended = Auth::check() ? Auth::user()->isRecommended($id) : true;
			$apply->isVoted = Auth::check() ? Auth::user()->isVoted($id) : true;

			return view('apply.show')->withApply($apply)->withIsWechat(
				strstr($request->header('user-agent'), config('business.WeChat_UA')) != false
			);
		}
		else{
			abort(404, 'Application not found.');
		}
	}

	public function postRecommendation(RecommendPostRequest $request)
	{
		$user = Auth::user();

		if ($user->isRecommendTooMuch())
		{
			return redirect()->back()->withMessage(['type' => 'error', 'content' => trans('message.recommend_too_much')]);
		}

		if ($user->isRecommended($request->applyid))
		{
			return redirect()->back()->withMessage(['type' => 'error', 'content' => trans('message.recommend_before')]);
		}

		$user->recommendations()->attach($request->applyid, ['content' => $request->content]);
		Apply::find($request->applyid)->increment('recommendations');

		return redirect()->back()->withMessage(['type' => 'success', 'content' => trans('message.recommend_successed')]);
	}

	public function getVote($id)
	{

		if (Auth::user()->isVoted($id))
		{
			return redirect()->back()->withMessage(['type' => 'error', 'content' => trans('message.voted_before')]);
		}

		$apply = Apply::find($id);

		if ($apply->type = config('business.type.college'))
		{
			if ($apply->college != Auth::user()->college)
			{
				return redirect()->back()->withMessage(['type' => 'error', 'content' => trans('message.vote_cross_college')]);
			}
		}

		$votes = Auth::user()->votes();

		if ($votes->count() >= config('business.vote.max'))
		{
			return redirect()->back()->withMessage(['type' => 'error', 'content' => trans('message.vote_too_much')]);
		}

		$votes->attach($id);
		Apply::find($id)->increment('votes');

		return redirect()->back()->withMessage(['type' => 'success', 'content' => trans('message.vote_successed')]);
	}

	public function getLike($id)
	{
		Apply::find($id)->increment('like');
	}
}
