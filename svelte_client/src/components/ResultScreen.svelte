
<script>
    import {respond} from "../store/RespondStore.js";
    import {replace} from "svelte-spa-router";

    function resetHandler(){
        respond.reset();
        replace("/")
    }
</script>

<div class="panel">
    <div class="panel-row"></div>
    <a href="#" on:click|preventDefault={resetHandler}>Вернуться в начало</a>
</div>
