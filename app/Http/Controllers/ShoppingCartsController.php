<?php

namespace Javan\Http\Controllers;

use Cart;
use Illuminate\Http\Request;
use Javan\Billing\BillingInterface;
use Javan\Jobs\SendOrderConfirmation;
use Javan\Jobs\SendOrderToAdmin;
use Javan\ShoppingCart;

class ShoppingCartsController extends Controller
{
	protected $billing;

	/**
	 * ShoppingCartsController constructor.
	 *
	 * @param \Javan\Billing\BillingInterface $billing
	 */
	public function __construct(BillingInterface $billing)
	{
		$this->middleware('auth');
		$this->middleware('admin.manager', ['except' => ['create', 'store', 'show']]);
		$this->billing = $billing;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$sortBy    = request()->get('sortBy');
		$direction = request()->get('direction');
		$params    = compact('sortBy', 'direction');

		if ($sortBy && $direction) {
			$carts = ShoppingCart::with('user')->orderBy($params['sortBy'], $params['direction'])->paginate(50);
		} else {
			$carts = ShoppingCart::with('user')->orderBy('created_at', 'DESC')->paginate(50);
		}

		$carts->transform(function($cart) {
			$cart->orders = unserialize($cart->orders);

			return $cart;
		});

		return view('cart.index', compact('carts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if (Cart::content()->isEmpty()) {
			return redirect('menu');
		}

		return view('cart.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'stripeToken' => 'required',
		]);
		$charge       = $this->billing->charge([
			'email' => auth()->user()->email,
			'token' => $request->input('stripeToken'),
		]);
		$ShoppingCart = ShoppingCart::create([
			'user_id'   => auth()->user()->id,
			'charge_id' => $charge->id,
			'orders'    => serialize(Cart::content()),
			'total'     => (int) Cart::total() * 100,
			'status'    => TRUE,
			'note'      => $request->input('note'),
		]);
		$this->dispatch(new SendOrderToAdmin($ShoppingCart));
		$this->dispatch(new SendOrderConfirmation($ShoppingCart));
		// TODO Job 3: Make PDF and attach it
		// $this->dispatch(new SendPdfAttachment($reservation));
		Cart::destroy();
		flash()->overlay('Payment was successfull', 'Delivery has been placed. We are going to send your delivery ASAP unless you have stated a specific delivery time in your delivery instructions. If you have any problems please call us on 020 8563 8553');

		return redirect()->route('member.orders');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Javan\ShoppingCart $cart
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, ShoppingCart $cart)
	{
		$refund = $this->billing->refund([
			'charge' => $cart->charge_id,
		]);

		$cart->update([
			'refund_id' => $refund->id,
			'status'    => FALSE,
			'note'      => '<p class="text-danger">Reject Reason: ' . $request->input('refund_reason') . '</p>',
		]);

		// TODO: Send refund Email with refund_reason

		flash()->success('Success', 'Payment has been refunded successfully');

		return back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \Javan\ShoppingCart $cart
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function destroy(ShoppingCart $cart)
	{
		$cart->delete();

		flash()->success('Success', 'Cart has been deleted successfully');

		return back();
	}
}