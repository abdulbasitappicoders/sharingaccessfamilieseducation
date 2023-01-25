<h2>{{$message}}</h2>
<script>
    let message = `{!! $message !!}`
    //console.log(message)
    // window.reactNativeWebView.postMessage("success");
    window.ReactNativeWebView.postMessage(message);
</script>
