<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Models\{UserCard,User,ChatListMessage,UserChildren};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\StripeClient;
use Exception;
use Auth;
use Pusher\Pusher;
use App\Events\SafePrivateEvent;



class UserCardController extends Controller
{
    public $stripe = "";

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function index(){
        try {
            $userCards = UserCard::where('user_id',Auth::user()->id)->with('user')->get();
            return apiresponse(true, 'payment methods found', $userCards);
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
        ]);
        if($validate->fails()){
            return apiresponse(false, implode("\n", $validate->errors()->all()));
        }
        try {
            $date = explode("/", $request->exp_date);
            $token = $this->stripe->tokens->create([
            'card' => [
                'number' => $request->card_number,
                'exp_month' => $date[0],
                'exp_year' => $date[1],
                'cvc' => $request->cvc,
            ],
            ]);
            $stripeCustomer = $this->stripe->customers->retrieve(Auth::user()->stripe_customer_id);
            $willBeDefault = ($stripeCustomer->default_source == null) ? true : false;
            $source = $this->stripe->customers->createSource(Auth::user()->stripe_customer_id, [
                'source' => $token
            ]);
            $userCard = UserCard::create([
                'user_id'           => Auth::user()->id,
                'stripe_source_id'  => $source->id,
                'default_card'      => $willBeDefault,
                'exp_date'          => $request->exp_date,
                'card_brand'        => $source->brand,
                'card_end_number'   => $source->last4,
            ]);
            $data['data'] = $userCard;
            return apiresponse(true,'Card saved',$userCard);
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
            $card = UserCard::find($request->card_id);
            if($card){
                $res = $this->stripe->customers->deleteSource(
                    Auth::user()->stripe_customer_id,
                    $card->stripe_source_id,
                    []
                  );
                  if($res){
                    $card->delete();
                    $data['card_id'] = $request->card_id;
                    return apiresponse(true,'Card deleted',$data);
                  }
            }else{
                return apiresponse(false,'Card Not found');
            }
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
        // return User::with('userCards')->get();
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
        // $user = auth()->user();
        // broadcast(new \App\Events\InitialRideEvent($user))->toOthers();
        // return json_encode(['id' => 12345]);
        // return $token = csrf_token();
        

        // if ($user) {
        //     $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
        //     echo $pusher->socket_auth('private_safeChannel', '10056.10742542');
        //     return;
        // }else {
        //     header('', true, 403);
        //     echo "Forbidden";
        //     return;
        // }
        $user = User::first();
        return $user->first_name;
    }

}
