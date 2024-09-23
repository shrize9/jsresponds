
<script>
    import {respond} from "../store/RespondStore.js";
    import {replace} from "svelte-spa-router";
    import {onDestroy, onMount} from "svelte";
    import {createRespondBroadcastClient} from "../rests/RespondBroadcastClient.js";
    import {notifications} from "../store/notifications.js";

    let topPlace ="Ожидаем результат";

    onMount(()=>{
        createRespondBroadcastClient().addCallback(getTopPlaceRespond);
    })
    onDestroy(()=>{
        createRespondBroadcastClient().removeCallback(getTopPlaceRespond);
    })

    function getTopPlaceRespond(message){
        const body =JSON.parse(message.data);
        if(body.message.type =="topPlace"){
            topPlace ="Вы заняли " +body.message.rank +" место среди всех ответивших";
            console.log(topPlace);
        }
    }

    function resetHandler(){
        respond.reset();
        replace("/")
    }
</script>

<div class="panel">
    <div class="panel-row">
        {topPlace}
    </div>
    <a href="#" on:click|preventDefault={resetHandler}>Вернуться в начало</a>
</div>
