<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;
use App\User;
use Storage;
use App\Traits\Helper;
class WebhookController extends Controller
{
    /**
     * Create a new webhook controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('services.stripe.webhook.secret')) {
            $this->middleware(VerifyWebhookSignature::class);
        }
    }

    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));
        
        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        }

        return $this->missingMethod();
    }

    /**
     * Handle customer subscription updated.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
            $data = $payload['data']['object'];

            $user->subscriptions->filter(function (Subscription $subscription) use ($data) {
                return $subscription->stripe_id === $data['id'];
            })->each(function (Subscription $subscription) use ($data) {
                // Quantity...
                if (isset($data['quantity'])) {
                    $subscription->quantity = $data['quantity'];
                }

                // Plan...
                if (isset($data['plan']['id'])) {
                    $subscription->stripe_plan = $data['plan']['id'];
                }

                // Trial ending date...
                if (isset($data['trial_end'])) {
                    $trial_ends = Carbon::createFromTimestamp($data['trial_end']);

                    if (! $subscription->trial_ends_at || $subscription->trial_ends_at->ne($trial_ends)) {
                        $subscription->trial_ends_at = $trial_ends;
                    }
                }

                // Cancellation date...
                if (isset($data['cancel_at_period_end']) && $data['cancel_at_period_end']) {
                    $subscription->ends_at = $subscription->onTrial()
                                ? $subscription->trial_ends_at
                                : Carbon::createFromTimestamp($data['current_period_end']);
                }

                $subscription->save();
            });
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
                $message = "\n ------------".date('H:i:s')."-stripe-------------\n";
                $message .= "\n --set-vip-vip-\n";
                $message .= $user?$user->name."quy":'test';
                $message .= "\n ------------end------------\n";
                Storage::prepend('logErrors/'.date('Y-m-d').'.log',$message);
                $user->vip = 'default';
                $user->is_trial = 1;
                $user->save();
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle customer updated.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerUpdated(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['id'])) {
            $user->updateCardFromStripe();
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle customer source deleted.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSourceDeleted(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $user->updateCardFromStripe();
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle deleted customer.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerDeleted(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['id']);

        if ($user) {
            $user->subscriptions->each(function (Subscription $subscription) {
                $subscription->skipTrial()->markAsCancelled();
            });

            $user->forceFill([
                'stripe_id' => null,
                'trial_ends_at' => null,
                'card_brand' => null,
                'card_last_four' => null,
            ])->save();
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Get the billable entity instance by Stripe ID.
     *
     * @param  string  $stripeId
     * @return \Laravel\Cashier\Billable
     */
    protected function getUserByStripeId($stripeId)
    {
        // $model = Cashier::stripeModel();
        // return (new $model)->where('stripe_id', $stripeId)->first();
        $user = User::where('stripe_id', $stripeId)->first();
        return User::find($user->id);
    }

    protected function handleSubscriptionScheduleExpiring(array $payload){
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
            $message = "\n ------------".date('H:i:s')."-stripe-------------\n";
            $message .= "\n --set-vip-default-\n";
            $message .= $user?$user->name:'test';
            $message .= "\n ------------end------------\n";
            Storage::prepend('logErrors/'.date('Y-m-d').'.log',$message);
            $user->vip = 'default';
            $user->is_trial = 1;
            $user->save();
        }

        return new Response('Webhook Handled', 200);
    }

    protected function handleCustomerSubscriptionTrialWillEnd(array $payload){
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
            $message = "\n ------------".date('H:i:s')."-stripe-------------\n";
            $message .= "\n --set-vip-is_trial-\n";
            $message .= $user?$user->name:'test';
            $message .= "\n ------------end------------\n";
            Storage::prepend('logErrors/'.date('Y-m-d').'.log',$message);
            $user->is_trial = 1;
            $user->save();
        }

        return new Response('Webhook Handled', 200);
    }   
   
    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function missingMethod($parameters = [])
    {
        return new Response;
    }
}
