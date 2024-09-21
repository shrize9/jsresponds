<script>
    import Router, {replace} from 'svelte-spa-router'
    import EnterScreen from "./components/EnterScreen.svelte";
    import {respond} from "./store/RespondStore.js";
    import {onMount} from "svelte";
    import AskScreen from "./components/AskScreen.svelte";
    import ResultScreen from "./components/ResultScreen.svelte";

    const routes = {
        '/': EnterScreen,
        '/ask': AskScreen,
        '/ask/:askId': AskScreen,
        '/result': ResultScreen
    }

    onMount(()=>{
    })
</script>

<main class="container">
    <Router {routes}/>
</main>

<style>

</style>
