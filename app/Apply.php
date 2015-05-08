<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;

class Apply extends Model {

	use SoftDeletes;

	protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function scopeType($query, $type)
	{
		return $query->whereType($type);
	}

	public function scopeCollege($query, $collegeid)
	{
		return $query->whereCollege($collegeid);
	}

	public function recommendations()
	{
		return $this->hasMany('App\Recommendation');
	}

	public function scopeOrder($query)
	{
		return $query->orderByRaw('rand()');
	}
}
