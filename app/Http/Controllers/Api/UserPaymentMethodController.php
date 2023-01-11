<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Stripe\StripeClient;
use Exception;
use Auth;
use Pusher\Pusher;
use App\Events\SafePrivateEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{UserPaymentMethod,User,ChatListMessage,UserChildren};
use App\Services\StripeService;

class UserPaymentMethodController extends Controller
{

    public function index(){
        try {
            $UserPaymentMethods = UserPaymentMethod::where('user_id',Auth::user()->id)->with('user')->get();
            return apiresponse(true, 'payment methods found', $UserPaymentMethods);
        } catch(Exception $e){
            return apiresponse(false, $e->getMessage());
        }
    }
    
    public function storeCard(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'card_number' => 'required',
            'exp_date' => 'required',
            'cvc' => 'required',
            'name' => 'required',
        ]);
        if($validate->fails()){
            return apiresponse(false, implode("\n", $validate->errors()->all()));
        }
        try {
            $user = auth()->user();
            $stripeService = new StripeService();
            $token = $stripeService->createToken($request);
            $stripeCustomer = $stripeService->getCustomer($user);
            $willBeDefault = $stripeCustomer->default_source == null ? true : false;
            $source = $stripeService->createSource($stripeCustomer->id, $token);
            $UserPaymentMethod = UserPaymentMethod::create([
                'user_id'           => Auth::user()->id,
                'stripe_source_id'  => $source->id,
                'default_card'      => $willBeDefault,
                'exp_date'          => $request->exp_date,
                'brand'             => $source->brand,
                'end_number'        => $source->last4,
                'name'              => $request->name,
            ]);
            $data['data'] = $UserPaymentMethod;
            return apiresponse(true,'Card saved',$UserPaymentMethod);
        } catch(Exception $e){
            return apiresponse(false, $e->getMessage());
        }
    }

    public function storeBank(Request $request){
        $validate = Validator::make($request->all(),[
            'bank_name' => 'required',
            'account_title' => 'required',
            'account_number' => 'required',
            'routing_number' => 'required',
        ]);
        if($validate->fails()){
            return apiresponse(false, implode("\n", $validate->errors()->all()));
        }
        try {
            // $token = $this->stripe->tokens->create([
            //     'bank_account' => [
            //         'account_holder_name' => Auth::user()->name,
            //         'country' => 'US',
            //         'currency' => 'usd',
            //         'account_number' => $request->account_number,
            //         ],
            //     ]);
            
            $UserPaymentMethod = UserPaymentMethod::find($request->id);
            if($UserPaymentMethod){
                $UserPaymentMethod->brand = $request->bank_name;
                $UserPaymentMethod->title = $request->account_title;
                $UserPaymentMethod->number = $request->account_number;
                $UserPaymentMethod->routing_number = $request->routing_number;
                $UserPaymentMethod->save();
            }else{
                // $source = $this->stripe->customers->createSource(Auth::user()->stripe_customer_id, [
                //     'source' => $token
                // ]);
                $UserPaymentMethod = UserPaymentMethod::create([
                    'user_id'           => Auth::user()->id,
                    'stripe_source_id'  => 134654,
                    'default_card'      => 1,
                    'brand'             => $request->bank_name,
                    'title'             => $request->account_title,
                    'number'            => $request->account_number,
                    'routing_number'    => $request->routing_number,
                    'type'              => 'bank_account',
                ]);
            }
            
            if($UserPaymentMethod){
                return apiresponse(true,'Account added',$UserPaymentMethod);
            }else{
                return apiresponse(false,'Account not added');
            }
            
        } catch(Exception $e){
            return apiresponse(false, $e->getMessage());
        }
    }

    public function deleteCard(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'card_id' => 'required',
        ]);
        if($validate->fails()){
            return apiresponse(false, implode("\n", $validate->errors()->all()));
        }
        try {
            $card = UserPaymentMethod::find($request->card_id);
            $user = $request->user();
            $stripeService = new StripeService();
            if($card) {
                $stripeCustomer = $stripeService->getCustomer($user);
                if ($stripeCustomer) {
                    $stripeService->deleteSource($stripeCustomer->id, $card->stripe_source_id);
                    $card->delete();
                    $data['card_id'] = $request->card_id;
                    return apiresponse(true,'Card deleted',$data);
                }
            }
                return apiresponse(false,'Card Not found');

        } catch(Exception $e){
            return apiresponse(false, $e->getMessage());
        }
    }

    public function PusherEndpoint($id){
        // $a = 10;
        // return $A;
        // $stripeCustomer = $this->stripe->customers->create([
        //     'email' => "charles@gmail.com",
        //     'name' => "Charles leclerc",
        // ]);
        // return User::with('UserPaymentMethods')->get();
        // return $ChatListMessages = ChatListMessage::where('chat_list_id',1)
        // ->with(['toUser' => function($q){
        //     $q->select('id');
        // }])->first();
        // return $stripeCustomer;
        // return $this->stripe->customers->retrieve("cus_MGIbhowFMXf5Dk");
        // $user = Auth::user();
        // $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
        // return response()->json($pusher);
        // if ($user->id) {
        //     $auth = PusherauthorizeChannel('SafePrivateEvent', '10056.10742542');
        //     return $auth;
        //     // $callback = str_replace('\\', '', $_GET['callback']);
        //     // header('Content-Type: application/javascript');
        //     echo($callback . '(' . $auth . ');');
        //   } else {
        //     header('', true, 403);
        //     echo "Forbidden";
        //   }
        // Debugbar::disable();
        $user = User::where('id',$id)->first();

        if ($user) {
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
            $pusher = $pusher->socket_auth('private_safeChannel', '10056.10742542');
            return $pusher;
        }else {
            header('', true, 403);
            echo "Forbidden";
            return;
        }
        
        // broadcast(new SafePrivateEvent($user))->toOthers();
    }

    public function test(Request $request){
        // return UserChildren::with('card')->get();
        $user = auth()->user();
        broadcast(new \App\Events\InitialRideEvent($user))->toOthers();
        return json_encode(['id' => 12345]);
        return $token = csrf_token();
        

        if ($user) {
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
            echo $pusher->socket_auth('private_safeChannel', '10056.10742542');
            return;
        }else {
            header('', true, 403);
            echo "Forbidden";
            return;
        }
    }
}
