<script>
    import {respond} from "../store/RespondStore.js"
    import {derived, writable} from "svelte/store"
    import {replace} from "svelte-spa-router";
    import {AskRest} from "../rests/AskRest.js";
    import {onMount} from "svelte";
    import {AnswerRest} from "../rests/AnswerRest.js";
    export let params;

    const SEND_ANSWER_BUTTON_TEXT ="Ответить";
    const GOTO_NEXT_BUTTON_TEXT   ="Следующий вопрос";
    const GOTO_FINISH_BUTTON_TEXT   ="Перейти к результату";

    let ask=writable({});
    let loaded =false;
    let needAnswerCorrect =false;
    let selectedIndex =0;
    let radioElements =[];
    let commandText =SEND_ANSWER_BUTTON_TEXT;

    onMount(()=>{
        if(params === undefined) {
            loadAsk();
        }else{
            loadAsk(params.askId);
        }

        if($respond.answers.length >= $respond.countQuestion){
            replace("/result")
        }
    })

    $: countAnswers =derived(respond, (respond)=>{
        return respond.answers.length +1
    })

    function loadAsk(askId){
        let request =null;
        if(askId ===undefined){
            request =(new AskRest()).next($respond.respondId)
        }else{
            request =(new AskRest()).load(askId)
        }

        request.then((_ask)=>{
            if(_ask.error ==false) {
                ask.set(_ask.data);
                respond.nextAsk(_ask);
                loaded = true;
            }
        })
    }

    function resetHandler(){
        respond.reset();
        replace("/")
    }

    function answerHandler(){
        const answer ={
            respondId:$respond.respondId,
            askId:$ask.id,
            answer:selectedIndex,
            answerCorrect:needAnswerCorrect
        };
        (new AnswerRest()).create(answer).then((json)=>{
            if(json.error ==false) {
                respond.addAnswer(answer);
                radioElements[$ask.correctAnswerIndex].classList.add("correct-answer");
                let labelAnswer =radioElements[$ask.correctAnswerIndex].querySelector('label');
                labelAnswer.innerHTML = labelAnswer.innerHTML + " выбрали " +json.data.answer.totalCorrectAnswer + " из " +json.data.answer.totalAnswers;

                if (selectedIndex != $ask.correctAnswerIndex) {
                    radioElements[selectedIndex].classList.add("wrong-answer");
                    let labelAnswer =radioElements[selectedIndex].querySelector('label');
                    labelAnswer.innerHTML = labelAnswer.innerHTML + " выбрали " +json.data.answer.totalAnswerIndex + " из " +json.data.answer.totalAnswers;
                }

                if(respond.canGetNextAsk()) {
                    commandText = GOTO_NEXT_BUTTON_TEXT;
                }else{
                    commandText = GOTO_FINISH_BUTTON_TEXT;
                }
            }else{

            }
        })
    }

    function finishHandler(){
        replace("/result")
    }

    function nextAskHandler(){
        loaded =false;
        needAnswerCorrect =false;
        selectedIndex =0;
        radioElements =[];
        commandText =SEND_ANSWER_BUTTON_TEXT;
        loadAsk();
    }
</script>

<div class="panel">
    {#if loaded}
        <div class="panel-row">
            {#if $countAnswers < $respond.countQuestion}
                <h3>Вопрос номер {$countAnswers} из {$respond.countQuestion}</h3>
            {:else}
                <h3>Последний вопрос</h3>
            {/if}
        </div>
        <div class="panel-row">
            <code>{$ask.question}</code>
        </div>
        <div class="panel-row">
            <ul class="answers-list">
                {#each $ask.answers as answer,index}
                    <li class="" bind:this={radioElements[index]}>
                        <div class="form-check">
                            <input class="form-check-input" name="answerIndex" type="radio" id="answerIndex_{index}" value={index} bind:group={selectedIndex}/>
                            <label class="form-check-label" for="answerIndex_{index}">{answer}
                                {#if commandText !=SEND_ANSWER_BUTTON_TEXT}
                                {/if}
                            </label>
                        </div>
                    </li>
                {/each}
            </ul>
        </div>
        <div class="panel-row">
            <input type="checkbox" id="answerCorrect" bind:checked={needAnswerCorrect}> <label for="answerCorrect">Хочу увидеть подсказку</label>
            <a href="#" class="btn btn-primary" on:click|preventDefault={()=>{if(commandText===SEND_ANSWER_BUTTON_TEXT) answerHandler(); else if(commandText===GOTO_FINISH_BUTTON_TEXT) finishHandler(); else nextAskHandler()}}>{commandText}</a>
            <a href="#" on:click|preventDefault={resetHandler}>Сбросить</a>
        </div>
        {#if needAnswerCorrect}
            <div class="panel-row">
                <small class="explanation">{$ask.explanation}</small>
            </div>
        {/if}

    {:else}
        Загрузка
    {/if}
</div>
