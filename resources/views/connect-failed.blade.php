<h2>{{$message}}</h2>
<script>
    let message = `{!! $message !!}`
    //console.log(message)
    // window.postMessage("failed");
    window.ReactNativeWebView.postMessage(message);
</script>
