import BaseRest from './BaseRest.js';

export class AskRest extends BaseRest{
    async next(respondId){
        return fetch(this.getFullUrl("api/asks/getNext/" +respondId))
                .then((response)=>response.json())
    }
    async load(askId){
        return fetch(this.getFullUrl("api/asks/" +askId))
            .then((response)=>response.json())
    }

}
