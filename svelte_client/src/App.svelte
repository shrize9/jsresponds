<script>
    import Router, {replace} from 'svelte-spa-router'
    import EnterScreen from "./components/EnterScreen.svelte";
    import {respond} from "./store/RespondStore.js";
    import {onDestroy, onMount} from "svelte";
    import AskScreen from "./components/AskScreen.svelte";
    import ResultScreen from "./components/ResultScreen.svelte";
    import {createRespondBroadcastClient} from "./rests/RespondBroadcastClient.js";
    import {notifications} from "./store/notifications.js";
    import Toast from "./components/Toast.svelte";

    const routes = {
        '/': EnterScreen,
        '/ask': AskScreen,
        '/ask/:askId': AskScreen,
        '/result': ResultScreen
    }

    onMount(()=>{
        (createRespondBroadcastClient()).addCallback(callbackHandler);
    })
    onDestroy(()=>{
        (createRespondBroadcastClient()).removeCallback(callbackHandler);
    })

    function callbackHandler(event){
        console.log("get message broadcast");
        console.log(event);
        const body =JSON.parse(event.data);
        if(body.message.type =="notify"){
            notifications.success(body.message.text, 3000)
        }
    }

</script>

<main class="container">
    <Router {routes}/>
    <Toast></Toast>
</main>

<style>

</style>
