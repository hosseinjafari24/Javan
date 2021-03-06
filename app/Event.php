<?php

namespace Javan;

use Cart;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	use Schedulable;
	protected $fillable = [
		'name',
		'description',
		'slug',
		'capacity',
		'price',
		'active',
		'image_path',
		'start',
		'finish',
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'created_at',
		'updated_at',
		'start',
		'finish',
	];

	/**
	 * @param $slug
	 * @return mixed
	 */
	public static function slug($slug)
	{
		return static::with('bookings')->whereSlug($slug)->firstOrFail();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function bookings()
	{
		return $this->hasMany(Booking::class);
	}

	/**
	 * @param $value
	 * @return string
	 */
	public function getNameAttribute($value)
	{
		return ucwords($value);
	}

	/**
	 * @param $query
	 * @return mixed
	 */
	public function scopeActive($query)
	{
		return $query->where('active', TRUE);
	}

	/**
	 * @return mixed
	 */
	public function seatsRemaining()
	{
		$bookings      = (int) Booking::active()->where('event_id', $this->id)->sum('seats');
		$cart_bookings = (int) Cart::instance('event')->count();

		$qty = $cart_bookings ? $cart_bookings + $bookings : $bookings;

		return $this->capacity - $qty;
	}
}
