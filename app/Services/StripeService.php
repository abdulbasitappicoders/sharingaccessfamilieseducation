<?php

namespace App\Services;

use Stripe\StripeClient;
use App\Models\PaymentMethod;

class StripeService
{
    private $stripe = null;

    public function __construct()
    {
        $this->setStripe(new StripeClient(config('payment.STRIPE_SECRET_KEY')));
    }

    /**
     * @return null
     */
    public function getStripe()
    {
        return $this->stripe;
    }

    /**
     * @param null $stripe
     */
    public function setStripe($stripe): void
    {
        $this->stripe = $stripe;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createCustomer($request)
    {
        return $this->getStripe()->customers->create([
            'email'     =>  $request->email,
            'name'      =>  $request->username,
        ]);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getCustomer($user)
    {
        if ($user->stripe_customer_id == null) {
            $stripeCustomer = $this->getStripe()->customers->create([
                'email' => $user->email,
                'name' => $user->username,
            ]);

            $user->update(['stripe_customer_id' => $stripeCustomer->id]);
            return $stripeCustomer;
        }

        return $this->getStripe()->customers->retrieve($user->stripe_customer_id);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getStripeCustomerId($user)
    {
        if ($user->stripe_customer_id != null) {
            return $user->stripe_customer_id;
        }

        $stripeCustomer = $this->getStripe()->customers->create([
            'email' => $user->email,
            'name' => $user->username,
        ]);

        $stripe_customer_id = $stripeCustomer->id;
        $user->update(['stripe_customer_id' => $stripe_customer_id]);

        return $user->stripe_customer_id;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createToken($request)
    {
        $date = explode("/", $request->exp_date);
        $token = $this->getStripe()->tokens->create([
            'card' => [
                'number' => $request->card_number,
                'exp_month' => $date[0],
                'exp_year' => $date[1],
                'cvc' => $request->cvc,
            ],
        ]);

        return $token;
    }

    /**
     * @param $customerId
     * @param $token
     * @return mixed
     */
    public function createSource($customerId, $token)
    {
        return $this->getStripe()->customers->createSource($customerId, [
            'source' => $token
        ]);
    }

    /**
     * @param $customerId
     * @param $sourceId
     * @return void
     */
    public function deleteSource($customerId, $sourceId)
    {
        $this->getStripe()->customers->deleteSource(
            $customerId,
            $sourceId,
            []
        );

        PaymentMethod::where('stripe_source_id', $sourceId)->delete();
    }

    /**
     * @param $customerId
     * @param $sourceId
     * @return mixed
     */
    public function updateToDefaultCard($customerId, $sourceId)
    {
        return $this->getStripe()->customers->update($customerId, [
            'default_source' => $sourceId
        ]);
    }

    public function createProduct($request) 
    {
        return $this->getStripe()->products->create([
            'name' => $request->name,
            'description' => $request->description
        ]);
    }

    public function createPlan($request, $product)
    {
        return $this->getStripe()->plans->create([
            'amount'    => $request->price * 100,
            'currency'  => 'usd',
            'interval'  => $request->interval_time,
            'product'   => $product,
        ]);
    }

    /**
     * @param $customer
     * @param $plan
     * @return mixed
     */
    public function buySubscription($customer, $planId)
    {
        return $this->getStripe()->subscriptions->create([
            'customer' => $customer,
            'items' => [
                ['price' => $planId],
            ],
        ]);
    }

    public function createCharge($tip, $customer,$source_id) 
    {
        return $this->getStripe()->charges->create([
            'customer'      => $customer,
            'amount'        => $tip * 100,
            'currency'      => 'usd',
            'source'        => $source_id,
            'description'   => 'Ride Charges',
        ]);
    }

    public function cancelSubscription($stripe_subscription_id)
    {
        return $this->getStripe()->subscriptions->cancel(
            $stripe_subscription_id,
            []
          );
    }
}