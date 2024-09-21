
export default class BaseRest{
    getFullUrl(suffixUrl){
        return import.meta.env.VITE_BASE_URL+ suffixUrl;
    }

    getWSFullUrl(suffixUrl){
        return import.meta.env.VITE_BASE_WS_URL+ suffixUrl;
    }
}
