<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnOrder;
use App\Models\Customer;
use Stripe\Stripe; 
use Stripe\Refund;
use Stripe\Account;
use Stripe\Payout;
use Stripe\Token;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class ReturnOrderController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'order_id'            => 'required|exists:orders,id',
            'reason'              => 'required|string',
            'bank_name'           => 'required|string',
            'account_holder_name' => 'required|string',
            'account_number'      => 'required|string',
            'ifsc_code'           => 'required|string',
        ]);

        $returnData = ReturnOrder::create([
            'customer_id'         => auth()->id() ?? session('customer_id'),
            'order_id'            => $request->order_id,
            'reason'              => $request->reason,
            'status'              => 'pending',
            'bank_name'           => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'account_number'      => $request->account_number,
            'ifsc_code'           => $request->ifsc_code,
			'refund_amount'       => $request->refund_amount,
        ]);

        return redirect()->back()->with('success', 'Your Return request is sent.');
    }

    public function processRefund($id) {
		$returnOrder = ReturnOrder::findOrFail($id);
		$customer  = Customer::findOrFail(session('customer_id'));
		Stripe::setApiKey(config('services.stripe.secret'));
		$stripe = new StripeClient('services.stripe.secret');

		try {
			$bankToken = Token::create([
				'bank_account' => [
					'country' => 'IN',
					'currency' => 'inr',
					'account_holder_name' => $returnOrder->account_holder_name,
					'account_holder_type' => 'individual',
					'routing_number' => $returnOrder->ifsc_code,
					'account_number' => $returnOrder->account_number,
				],
			]);
/*	
			$account = $stripe->accounts->create([
				'country' => 'US',
				'email' => 'jenny.rosen@example.com',
				'controller' => [
				'fees' => ['payer' => 'application'],
				'losses' => ['payments' => 'application'],
				'stripe_dashboard' => ['type' => 'express'],
				],
			]);*/
			
			$account = Account::create([
				'type' => 'custom',
				'country' => 'IN',
				'email' => $customer->email,
				'external_account' => $bankToken->id,
				'capabilities' => [
					'transfers' => ['requested' => true],
				],
			]);

			$transfer = \Stripe\Transfer::create([
				'amount' => (int)($returnOrder->refund_amount * 100),
				'currency' => 'inr',
				'destination' => $account->id,
			]);

			Payout::create([
				'amount' => (int)($returnOrder->refund_amount * 100),
				'currency' => 'inr',
			], ['stripe_account' => $account->id]);

			$returnOrder->update([
				'status' => 'refunded',
				'refund_amount'  => $transfer->amount,
				'stripe_refund_id' => $transfer->id,
			]);

			return redirect()->back()->with('success', 'Money transferred to bank account successfully!');

		} catch (\Exception $e) {
			Log::error('Payout Error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Transfer failed: ' . $e->getMessage());
		}
	}
	public function index() {
		$returns = ReturnOrder::with('order')->latest()->paginate(10);
		return view('admin.returns.index', compact('returns'));
	}

	public function show($id) {
		$returnOrder = ReturnOrder::with(['order', 'customer'])->findOrFail($id);
		return view('admin.returns.show', compact('returnOrder'));
	}

}
