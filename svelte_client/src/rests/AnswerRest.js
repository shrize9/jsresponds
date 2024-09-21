import BaseRest from './BaseRest.js';

export class AnswerRest extends BaseRest{
    async create(answer){
        return fetch(this.getFullUrl("api/answer/"),{
                method: 'POST',
                body: JSON.stringify(answer)
            }).then((response)=>response.json())
    }
}
