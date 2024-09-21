import BaseRest from './BaseRest.js';

export class RespondRest extends BaseRest{
    async create(name, countAsk){
        return fetch(  this.getFullUrl("api/respond/"), {
            method: 'POST',
            body: JSON.stringify({
                name:name,
                countQuestion:countAsk
            })
        }).then((response)=>response.json())
    }

    async load(respondId){
        return fetch(this.getFullUrl("api/respond/"), {method:'GET', headers:{"respondId":respondId}}).then((response)=>response.json())
    }

    async empty(){
        return fetch(this.getFullUrl("api/respond/")).then((response)=>response.json())
    }
}
