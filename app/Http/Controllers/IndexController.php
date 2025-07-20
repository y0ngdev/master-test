<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Testimonial;
use App\Models\Transaction;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class IndexController extends Controller
{
    public function index(): Response
    {
        //        dd( Plan::first());
        return Inertia::render('Welcome', [
            'csrf_token' => csrf_token(),
            'plan' => Plan::query()->first(),
            'testimonials' => Testimonial::published()->orderBy('order')->get()->all(),
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    public function buy(Request $request)
    {
        Stripe::setApiKey(config('payments.drivers.stripe.secret_key'));

        $priceId = $request->input('priceId');
        //        dd($priceId);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('buy.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('homepage'),
        ]);

        return redirect($session->url);

    }

    /**
     * @throws ApiErrorException
     */
    public function success(Request $request)
    {

        Stripe::setApiKey(config('payments.drivers.stripe.secret_key'));

        $sessionId = $request->get('session_id');

        abort_unless($sessionId, 400, 'Session ID missing');

        $session = Session::retrieve($sessionId);

        abort_if($session->payment_status !== 'paid', 400, 'Payment not completed');

        $email = $session->customer_details->email ?? null;
        $name = $session->customer_details->name ?? 'New User';
        abort_unless($email, 400, 'Customer email not found');

        $user = User::query()->firstOrCreate(['email' => $email], ['name' => $name, 'password' => bcrypt(Str::random(16))]);

        \App\Models\Transaction::query()->firstOrCreate(['checkout_session_id' => $session['id']], [
            'user_id' => $user->id,
            'amount' => $session['amount_total'] / 100,
            'currency' => strtoupper((string) $session['currency']),
            'status' => 'successful',
        ]);

        Auth::login($user);

        Password::sendResetLink(['email' => $user->email]);

        return redirect(route('dashboard'))->with('toast', [
            'type' => 'success',
            'message' => 'Welcome! A link to reset your password has been sent',
        ]);

    }
}
